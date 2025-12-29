<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class UserEmailExistRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
        ];
    }
}
