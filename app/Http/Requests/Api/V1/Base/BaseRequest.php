<?php

namespace App\Http\Requests\Api\V1\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;

abstract class BaseRequest extends FormRequest
{
    /**
     * Override failed validation to match API format.
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();

        $response = [
            'status'  => false,
            'code'    => 422,
            'message' => 'Validation errors',
            'error'   => $errors
        ];

        throw new HttpResponseException(
            response()->json($response, 422)
        );
    }

    /**
     * Prevent Laravel from redirecting JSON requests.
     */
    public function wantsJson()
    {
        return true;
    }
}
