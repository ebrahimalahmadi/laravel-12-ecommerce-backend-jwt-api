<?php

namespace App\Http\Requests\Api\V1\Auth\User;

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
            'name' => ['required', 'string', 'max:60', 'min:3', 'unique:users'],
            'email' => ['required', 'string', 'lowercase', 'ends_with:gmail.com', 'min:10', 'max:50', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:30', 'confirmed'], // 'confirmed' تتطلب حقل password_confirmation
        ];
    }

    public function messages()
    {
        return [
            // Name
            'name.required' => 'Please enter your your Name.',
            'name.string'   => 'Name must be a valid text.',
            'name.min'      => 'Name must be at least 3 characters long.',
            'name.max'      => 'Name cannot exceed 60 characters.',
            'name.unique'   => 'This name is already taken. Please choose another.',

            // Email
            'email.required' => 'Please enter your email address.',
            'email.string'   => 'Email must be a valid text.',
            'email.email'    => 'Please provide a valid email address (e.g., example@gmail.com).',
            'email.lowercase' => 'Email must be in lowercase letters.',
            'email.ends_with' => 'Email must be a Gmail address (ending with @gmail.com).',
            'email.min'      => 'Email must be at least 10 characters long.',
            'email.max'      => 'Email cannot exceed 50 characters.',
            'email.unique'   => 'This email is already registered. Try logging in.',

            // Password
            'password.required'  => 'Please enter a password.',
            'password.string'    => 'Password must be a valid text.',
            'password.min'       => 'Password must be at least 6 characters.',
            'password.max'       => 'Password cannot exceed 30 characters.',
            'password.confirmed' => 'Password confirmation does not match. Please try again.',
        ];
    }
}
