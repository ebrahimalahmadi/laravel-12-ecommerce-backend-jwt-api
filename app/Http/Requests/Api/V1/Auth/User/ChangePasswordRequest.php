<?php

namespace App\Http\Requests\Api\V1\Auth\User;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::validationError(errors: $validator->errors())
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',  // new_password_confirmation
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Current password is required',
            'new_password.required'     => 'New password is required',
            'new_password.confirmed'    => 'New password confirmation does not match',
        ];
    }
}

// {
//     "current_password": "123456",
//     "new_password": "1234567",
//     "new_password_confirmation": "1234567"
// }
