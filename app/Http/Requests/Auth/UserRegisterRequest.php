<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class UserRegisterRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}
