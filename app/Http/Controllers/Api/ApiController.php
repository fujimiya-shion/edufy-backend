<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 *
 * @OA\Get(
 *     path="/sanctum/csrf-cookie",
 *     summary="initialize CSRF",
 *     tags={"Initialize"},
 *      @OA\Response(
 *          response=204,
 *          description="Successful operation",
 *       )
 * )
 */
class ApiController extends Controller
{
    public function success(?string $message = null, $data = null, int $status = 200, array $customData = []): JsonResponse
    {
        return response()->json(array_merge($customData, [
            'status' => true,
            'status_code' => $status,
            'message' => $message,
            'data' => $data,
        ]), $status);
    }

    public function error(?string $message = null, int $status = 500, $errors = null): JsonResponse
    {
        return response()->json([
            'status' => false,
            'status_code' => $status,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    public function notFound(string $message = 'Not Found!!!', int $status = 404, $errors = null): JsonResponse {
        return $this->error($message, $status, $errors);
    }

    public function unauthorized(string $message = 'Unauthorized', int $status = 401, $errors = null): JsonResponse {
        return $this->error($message, $status, $errors);
    }
}
