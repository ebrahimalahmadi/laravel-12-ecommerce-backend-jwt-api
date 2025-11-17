<?php

namespace App\Http\Requests\Api\V1\Auth\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'min:5', 'max:60', 'ends_with:gmail.com', 'lowercase', 'exists:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:30'],
        ];
    }

    public function messages()
    {
        return [
            // Email
            'email.required'   => 'Please enter your email address.',
            'email.string'     => 'Email must be a valid text.',
            'email.email'      => 'Please provide a valid email address (e.g., example@gmail.com).',
            'email.min'        => 'Email must be at least 5 characters long.',
            'email.max'        => 'Email cannot exceed 60 characters.',
            'email.ends_with'  => 'Email must be a Gmail address (ending with @gmail.com).',
            'email.lowercase'  => 'Email must be in lowercase letters.',
            'email.exists'     => 'No account found with this email. Please register first.',

            // Password
            'password.required' => 'Please enter your password.',
            'password.string'   => 'Password must be a valid text.',
            'password.min'      => 'Password must be at least 6 characters long.',
            'password.max'      => 'Password cannot exceed 30 characters.',
        ];
    }
}
