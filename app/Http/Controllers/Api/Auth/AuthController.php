<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Api\Auth\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    // Login user and return JWT token
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => new UserResource(auth('api')->user()),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ], 200);
    }

    // // Register new user and return JWT token
    public function register(RegisterRequest $request)
    {
        // إنشاء مستخدم جديد بعد التحقق من البيانات وحفظه في قاعدة البيانات
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);


        // إنشاء التوكن مباشرة بعد التسجيل
        $token = auth('api')->login($user);


        // إرجاع استجابة API موحدة
        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'data' => [
                // 'user' => $user,
                'user' => new UserResource($user),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ]
        ], 201);
    }

    // Logout user
    // تسجيل خروج المستخدم وحذف Token.
    public function logout()
    {

        auth('api')->logout();

        return response()->json([
            'status' => true,
            'message' => 'successfully logged out!',
            'data' => [null]
        ], 200);
    }

    // Get authenticated user
    public function profile()
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'User retrieved successfully',
            'data' => [
                // 'user' => $user,
                'user' => new UserResource($user)
            ]
        ], 200);
    }

    // Refresh JWT token
    public function refresh()
    {
        $refreshToken = auth('api')->refresh();

        return response()->json([
            'status' => true,
            'message' => 'Token refreshed successfully',
            'data' => [
                'access_token' => $refreshToken,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ], 200);
    }
}
