<?php

namespace App\Http\Requests\Api\Auth;

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
        // 1. التحقق من صحة البيانات
        return [
            //
            'name' => ['required', 'string', 'max:60', 'min:3', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:30', 'min:6', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'], // 'confirmed' تتطلب حقل password_confirmation
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
        ];
    }
}
