<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class UserLoginRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }
}
