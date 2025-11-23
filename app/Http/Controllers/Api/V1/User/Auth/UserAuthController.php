<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\User\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\Auth\User\RegisterRequest;
use App\Http\Resources\Api\V1\User\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    /**
     *  User Login (JWT)
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = auth('user')->attempt($credentials)) {
                return ApiResponse::unauthorized('Invalid email or password');
            }

            $user = auth('user')->user();

            return ApiResponse::success(
                'User logged in successfully',
                [
                    'token_type'   => 'bearer',
                    'access_token' => $token,
                    'expires_in'   => auth('user')->factory()->getTTL() * 60,
                    'user'         => new UserResource($user),
                ]
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }


    /**
     *  Register new User and issue JWT
     */
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            $user = User::create(attributes: $data);
            $token = auth('user')->login($user);

            return ApiResponse::success(
                'User registered successfully',
                [
                    'token_type'   => 'bearer',
                    'access_token' => $token,
                    'expires_in'   => auth('user')->factory()->getTTL() * 60,
                    'user'         => new UserResource($user),
                ],
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }


    /**
     *  User Logout
     */
    public function logout()
    {
        try {
            auth('user')->logout();

            return ApiResponse::success(
                'User logged out successfully',
                ['user' => null]
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }


    /**
     *  Refresh JWT Token
     */
    public function refresh()
    {
        try {
            $newToken = auth('user')->refresh();
            $user = auth('user')->user();

            return ApiResponse::success(
                'Token refreshed successfully',
                [
                    'token_type'   => 'bearer',
                    'access_token' => $newToken,
                    'expires_in'   => auth('user')->factory()->getTTL() * 60,
                    'user'         => new UserResource($user),
                ]
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }
}
