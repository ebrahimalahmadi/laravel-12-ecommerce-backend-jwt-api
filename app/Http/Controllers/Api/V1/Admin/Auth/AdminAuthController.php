<?php

namespace App\Http\Controllers\Api\V1\Admin\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\Admin\LoginRequest;
use App\Http\Resources\Api\V1\Admin\AdminResource;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{

    /**
     *  Admin Login (JWT)
     */
    public function login(LoginRequest $request)
    {
        try {

            $credentials = $request->only('email', 'password');

            // If Wrong email or password
            if (!$token = auth('admin')->attempt($credentials)) {
                return ApiResponse::unauthorized('Invalid email or password');
            }

            $admin = auth('admin')->user();

            return ApiResponse::success(
                'Admin logged in successfully',
                [
                    'token_type'   => 'bearer',
                    'access_token' => $token,
                    'expires_in'   => auth('admin')->factory()->getTTL() * 60,
                    'admin'        => new AdminResource($admin)
                ]
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }

    /**
     *  Logout Admin
     */
    public function logout()
    {
        try {
            auth('admin')->logout();

            return ApiResponse::success(
                'Admin logged out successfully',
                ['admin' => null]
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }

    /**
     *  Refresh Token
     */
    public function refresh()
    {
        try {
            $newToken = auth('admin')->refresh();
            $admin = auth('admin')->user();

            return ApiResponse::success(
                'Token refreshed successfully',
                [
                    'token_type'   => 'bearer',
                    'access_token' => $newToken,
                    'expires_in'   => auth('admin')->factory()->getTTL() * 60,
                    'admin'        => new AdminResource($admin)
                ]
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }
}
