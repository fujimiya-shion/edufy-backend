<?php

namespace App\Filament\Pages\Auth;

use App\Models\Admin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        $credentials = $this->form->getState();
        $credentials['status'] = Admin::STATUS_PUBLIC;
        $remember = $this->form->getState()['remember'] ?? false;
        unset($credentials['remember']);

        if (!auth()->guard('admin')->attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $admin = auth()->guard('admin')->user();

        if (!$admin->is_email_verified) {
            $admin->email_verified_at = now();
            $admin->save();
        }

        return app(LoginResponse::class);
    }

}
