<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class UserResetPasswordRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}
