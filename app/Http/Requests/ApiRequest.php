<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;

abstract class ApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Standardize validation errors to match existing API response structure.
     */
    protected function failedValidation(Validator $validator): void
    {
        $response = Response::json([
            'status' => false,
            'status_code' => 422,
            'message' => Lang::get('Lỗi xác thực dữ liệu'),
            'errors' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}

