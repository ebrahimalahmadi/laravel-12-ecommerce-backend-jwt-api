<?php

namespace App\Http\Controllers\Api\V1\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\Admin\LoginRequest;
use App\Http\Requests\Api\V1\Auth\Admin\RegisterRequest;
use App\Http\Resources\Api\V1\Admin\AdminResource;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // Register new admin and return JWT token
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $admin = Admin::create(attributes: $data);

        $token = auth('admin')->login($admin);

        return response()->json([
            'status'       => true,
            'code'         => 201,
            'message'      => 'Admin registered successfully',
            'token_type'   => 'bearer',
            'access_token' => $token,
            'expires_in'   => auth('admin')->factory()->getTTL() * 60,
            'data'         => [
                'admin' => new AdminResource($admin),
            ],
        ], 201);
    }

    // Login admin and return JWT token
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('admin')->attempt($credentials)) {
            return response()->json([
                'status'  => false,
                'code'    => 401,
                'message' => 'Invalid email or password',
            ], 401);
        }

        $admin = auth('admin')->user();

        return response()->json([
            'status'       => true,
            'code'         => 200,
            'message'      => 'Admin logged in successfully',
            'token_type'   => 'bearer',
            'access_token' => $token,
            'expires_in'   => auth('admin')->factory()->getTTL() * 60,
            'data'         => [
                'admin' => new AdminResource($admin),
            ],
        ], 200);
    }

    // Logout admin
    public function logout()
    {
        auth('admin')->logout();

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => 'Admin logged out successfully',
            'data'    => [
                'admin' => null,
            ],
        ], 200);
    }

    // Refresh token
    public function refresh()
    {
        $newToken = auth('admin')->refresh();
        $admin = auth('admin')->user();

        return response()->json([
            'status'       => true,
            'code'         => 200,
            'message'      => 'Token refreshed successfully',
            'token_type'   => 'bearer',
            'access_token' => $newToken,
            'expires_in'   => auth('admin')->factory()->getTTL() * 60,
            'data'         => [
                'admin' => new AdminResource($admin),
            ],
        ], 200);
    }
}
