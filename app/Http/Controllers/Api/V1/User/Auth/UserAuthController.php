<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\User\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\Auth\User\RegisterRequest;
use App\Http\Resources\Api\V1\User\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{

    // Login user and return JWT token
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('user')->attempt($credentials)) {
            return response()->json([
                'status'  => false,
                'code'    => 401,
                'message' => 'Invalid email or password',
            ], 401);
        }

        $user = auth('user')->user();

        return response()->json([
            'status'       => true,
            'code'         => 200,
            'message'      => 'User logged in successfully',
            'token_type'   => 'bearer',
            'access_token' => $token,
            'expires_in'   => auth('user')->factory()->getTTL() * 60,
            'data'         => [
                'user' => new UserResource($user),
            ],
        ], 200);
    }


    // Register new user and return JWT token
    public function register(RegisterRequest $request)
    {
        // جلب البيانات بعد التحقق
        $data = $request->validated();

        // تشفير كلمة المرور
        $data['password'] = Hash::make($data['password']);

        $user = User::create(attributes: $data);

        // إنشاء التوكن بعد التسجيل مباشرة
        $token = auth('user')->login($user);


        return response()->json([
            'status'      => true,
            'code'        => 201,
            'message'     => 'User registered successfully',
            'token_type'  => 'bearer',
            'access_token' => $token,
            'expires_in'  => auth('user')->factory()->getTTL() * 60,
            'data'        => [
                'user' => new UserResource($user),
            ],
        ], 201);
    }


    // User logout and token deletion
    public function logout()
    {
        auth('user')->logout();

        return response()->json([
            'status'       => true,
            'code'         => 200,
            'message'      => 'User logged out successfully',
            'data'         => [
                'user' => null,
            ],
        ], 200);
    }


    // Refresh token and return new token
    public function refresh()
    {
        // إنشاء توكن جديد
        $newToken = auth('user')->refresh();

        // جلب المستخدم الحالي
        $user = auth('user')->user();

        return response()->json([
            'status'       => true,
            'code'         => 200,
            'message'      => 'Token refreshed successfully',
            'token_type'   => 'bearer',
            'access_token' => $newToken,
            'expires_in'   => auth('user')->factory()->getTTL() * 60,
            'data'         => [
                'user' => new UserResource($user),
            ],
        ], 200);
    }
}
