<?php

namespace App\Http\Requests\Api\V1\Auth\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
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
            //
            'name' => ['required', 'string', 'min:3', 'max:30'],
            'email' => ['required', 'string', 'email', 'min:5', 'max:60', 'ends_with:gmail.com', 'lowercase', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:6', 'max:30'],
        ];
    }
}
