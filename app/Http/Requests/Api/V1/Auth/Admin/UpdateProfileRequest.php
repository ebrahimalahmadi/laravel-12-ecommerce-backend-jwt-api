<?php

namespace App\Http\Requests\Api\V1\Auth\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;

class UpdateProfileRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:3', 'max:40'],
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => [
                'nullable',
                'string',
                'min:13',
                'max:13',
                'regex:/^\+967\d{9}$/',
                //         // السماح بالمستخدم الحالي نفسه بالاحتفاظ بالرقم
                'unique:users,phone,' . auth('user')->id(),
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.string'   => 'Name must be a valid text.',
            'name.min'      => 'Name must be at least 3 characters long.',
            'name.max'      => 'Name must not exceed 40 characters.',

            'phone.string'  => 'Phone number must be a valid text.',
            'phone.min'     => 'Phone number must be exactly 13 characters long.',
            'phone.max'     => 'Phone number must be exactly 13 characters long.',
            'phone.regex'   => 'Phone number must start with +967 followed by 9 digits (e.g. +967123456789).',

            'avatar.image'  => 'Avatar must be an image file.',
            'avatar.mimes'  => 'Avatar must be jpeg, png, jpg, or gif.',
            'avatar.max'    => 'Avatar must not exceed 2MB.',
        ];
    }
}
