<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class UserForgotPasswordRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string'],
        ];
    }
}
