<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class DeveloperLoginRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }
}
