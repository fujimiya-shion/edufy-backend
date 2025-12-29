<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class UserPhoneExistRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string'],
        ];
    }
}
