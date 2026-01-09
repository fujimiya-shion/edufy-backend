<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Auth\UserEmailExistRequest;
use App\Http\Requests\Auth\UserForgotPasswordRequest;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserPhoneExistRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Requests\Auth\UserResetPasswordRequest;
use App\Models\User;
use App\Services\Contracts\Account\IUserService;
use App\Traits\SanctumAuth;
use App\Utils\StringUtil;
use Carbon\Carbon;
use DB;
use Exception;
use Google_Client;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class UserAuthController extends ApiController
{
    use SanctumAuth;
    protected IUserService $service;

    public function __construct(IUserService $service)
    {
        $this->service = $service;
    }

    public function register(UserRegisterRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        try {
            $user = $this->service->create([
                'phone'     => $data['phone'],
                'password'  => $data['password'],
                'full_name' => '', // Set empty string as default, user can update later
            ]);

            $token = StringUtil::genToken();
            $this->service->update($user->id, ['token' => $token]);

            return $this->success(
                'Đăng ký tài khoản thành công',
                [
                    'user'  => $user,
                    'token' => $token,
                ],
                201
            );
        } catch (\Throwable $e) {
            report($e);

            return $this->error('Đăng ký thất bại', 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/auth/user/login",
     *     tags={"Authentication"},
     *     summary="Login by phone",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone", "password"},
     *             @OA\Property(property="phone", type="string", example="0901234567"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=401, description="Invalid credentials"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function login(UserLoginRequest $request)
    {
        $data = $request->validated();

        // Tìm user theo phone
        /** @var \App\Models\User|null $user */
        $user = User::where('phone', $data['phone'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->unauthorized(__('Mật khẩu hoặc số điện thoại không chính xác'));
        }

        // Reject deleted accounts
        if ($user->status === UserStatus::DELETED) {
            return $this->error('Tài khoản của bạn đã bị xóa. Vui lòng liên hệ hỗ trợ nếu bạn cần khôi phục.', 403);
        }

        $token = $user->token;

        if ($token === null) {
            $token = StringUtil::genToken();
            $this->service->update($user->id, ['token' => $token]);
        }

        return $this->success('Đăng nhập thành công', [
            'user'  => $user,
            'token' => $token,
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/auth/user/forgot-password",
     *     tags={"Authentication"},
     *     summary="Request password reset via phone",
     *     description="Generate OTP or token for password reset and send it via SMS or other channel",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone"},
     *             @OA\Property(property="phone", type="string", example="0901234567")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Reset OTP generated and sent"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function forgotPassword(UserForgotPasswordRequest $request)
    {
        $data = $request->validated();

        $user = User::where('phone', $data['phone'])->first();

        if (!$user) {
            return $this->error('Không tìm thấy người dùng', 404);
        }

        // Reject deleted accounts
        if ($user->status === UserStatus::DELETED) {
            return $this->error('Tài khoản của bạn đã bị xóa. Vui lòng liên hệ hỗ trợ nếu bạn cần khôi phục.', 403);
        }

        $token = (string) random_int(100000, 999999);

        $user->token = $token;
        $user->save();

        // TODO: gửi SMS ở đây (tùy vào service em tích hợp)
        // SmsService::sendResetOtp($user->phone, $token);

        return $this->success('Mã OTP đặt lại mật khẩu đã được gửi', [
            'phone' => $user->phone,
            // NOTE: nếu muốn debug ở môi trường dev có thể trả OTP ra:
            // 'otp' => $token,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/user/reset-password",
     *     tags={"Authentication"},
     *     summary="Reset password by phone and OTP",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone", "token", "password", "password_confirmation"},
     *             @OA\Property(property="phone", type="string", example="0901234567"),
     *             @OA\Property(property="token", type="string", example="123456", description="OTP or reset token"),
     *             @OA\Property(property="password", type="string", format="password", example="newPassword123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newPassword123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Password reset successfully"),
     *     @OA\Response(response=400, description="Invalid phone or token"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function resetPassword(UserResetPasswordRequest $request)
    {
        $data = $request->validated();

        $user = User::where('phone', $data['phone'])
            ->where('token', $data['token'])
            ->first();

        if (!$user) {
            return $this->error('Số điện thoại hoặc mã token không hợp lệ', 400);
        }

        // Reject deleted accounts
        if ($user->status === UserStatus::DELETED) {
            return $this->error('Tài khoản của bạn đã bị xóa. Vui lòng liên hệ hỗ trợ nếu bạn cần khôi phục.', 403);
        }

        $user->password = Hash::make($data['password']);
        $user->token = null; // clear OTP
        $user->save();

        return $this->success('Đặt lại mật khẩu thành công');
    }


    /**
     * @OA\Get(
     *     path="/api/v1/auth/user/me",
     *     tags={"Authentication"},
     *     summary="Get current authenticated user",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="User data"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function me(Request $request)
    {
        $token = StringUtil::getUserToken($request);
        $user = $this->service->getBy(['token' => $token], [
            
        ])->first();

        if (!$user) {
            return $this->unauthorized('Không được phép truy cập');
        }

        return $this->success('Lấy thông tin người dùng thành công', $user);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/user/email-exist",
     *     tags={"Authentication"},
     *     summary="Check if email already exists",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email checked",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Email checked successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="exists", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function emailExist(UserEmailExistRequest $request)
    {
        $data = $request->validated();

        // $exists = $this->service->count([
        //     'email' => $data['email'],
        // ]) > 0;

        $existsUser = $this->service->getBy(['email' => $data['email'], 'password' => ['!=', null]])->first();
        $exists = $existsUser ? true : false;
        $fullName = $existsUser ? $existsUser->full_name : null;

        return $this->success('Kiểm tra email thành công', [
            'exists' => $exists,
            'full_name' => $fullName,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/user/phone-exist",
     *     tags={"Authentication"},
     *     summary="Check if phone already exists",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone"},
     *             @OA\Property(
     *                 property="phone",
     *                 type="string",
     *                 example="0901234567"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Phone checked",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Phone checked successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="exists", type="boolean", example=true),
     *                 @OA\Property(property="full_name", type="string", example="Nguyen Van A"),
     *                 @OA\Property(property="phone", type="string", example="0901234567")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function phoneExist(UserPhoneExistRequest $request)
    {
        $data = $request->validated();

        $existsUser = $this->service->getBy(['phone' => $data['phone']])->first();
        $exists     = $existsUser ? true : false;
        $fullName   = $existsUser ? $existsUser->full_name : null;
        $phone      = $existsUser ? $existsUser->phone : $data['phone'];

        return $this->success('Kiểm tra số điện thoại thành công', [
            'exists'    => $exists,
            'full_name' => $fullName,
            'phone'     => $phone,
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/auth/user/role",
     *     tags={"Authentication"},
     *     summary="Update current user role",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role"},
     *             @OA\Property(
     *                 property="role",
     *                 type="string",
     *                 example="talent",
     *                 description="Role value, must be a valid UserRole enum"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Role is not valid"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Something went wrong"
     *     )
     * )
     */
    public function role(Request $request) {
        $rawRole = $request->role;
        $role = UserRole::tryFrom($rawRole);
        if(!$role)
            return $this->error('Vai trò không hợp lệ', 422);
        
        $token = StringUtil::getUserToken($request);
        try {
            $user = $this->service->getBy(['token' => $token])->first();
            $this->service->update($user->id, ['role' => $role->value]);
            return $this->success('Cập nhật vai trò thành công');
        }
        catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Đã có lỗi xảy ra');
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/auth/user/google",
     *     tags={"Authentication"},
     *     summary="Login or register with Google",
     *     description="Verify Google ID token, then login existing user or create a new one.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_token"},
     *             @OA\Property(
     *                 property="id_token",
     *                 type="string",
     *                 example="eyJhbGciOiJSUzI1NiIsImtpZCI6Ij..."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully login by Google",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Successfully login by Google"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="user-session-token-xyz"),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     description="User object"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid Google ID Token"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Something went wrong"
     *     )
     * )
     */
    public function google(Request $request) {
        \Firebase\JWT\JWT::$leeway = 60; // FIX LỖI iat prior to, cho phép lệch vài giây

        $idToken = $request->id_token;
        $clientId = env('GOOGLE_CLIENT_ID');
        $clientSecret = env('GOOGLE_CLIENT_SECRET');

        $googleClient = new Google_Client();
        $googleClient->setRedirectUri('postmessage');
        $googleClient->setClientId($clientId);
        $googleClient->setClientSecret($clientSecret);

        $payload = $googleClient->verifyIdToken($idToken);

        if(!$payload)
            return $this->error("Token Google ID không hợp lệ", 401);

        $email         = $payload['email'] ?? null;
        $emailVerified = $payload['email_verified'] ?? false;
        $name          = $payload['name'] ?? $email;
        $avatar        = $payload['picture'] ?? null;

        try {
            DB::beginTransaction();

            $user = $this->service->getBy(['email' => $email])->first();
            
            if ($user) {
                $token = $user->token ?? StringUtil::genToken();
                if (!$user->token) {
                    $this->service->update($user->id, ['token' => $token]);
                }

                DB::commit();

                return $this->success('Đăng nhập bằng Google thành công', [
                    'token' => $token,
                    'user'  => $user,
                ]);
            }

            $token = StringUtil::genToken();
            $user = $this->service->create([
                'email'             => $email,
                'name'         => $name,
                'avatar_url'        => $avatar,
                'email_verified_at' => $emailVerified ? Carbon::now() : null,
                'token'             => $token,
            ]);

            DB::commit();

            return $this->success('Đăng nhập bằng Google thành công', [
                'token' => $token,
                'user'  => $user,
            ]);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return $this->error('Đã có lỗi xảy ra');
        }
    }


}
