<?php

use App\Http\Controllers\Api\V1\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Api\V1\Admin\Auth\AdminProfileController;
use App\Http\Controllers\Api\V1\Admin\User\UserManagementController;
use App\Http\Controllers\Api\V1\User\Auth\UserAuthController;
use App\Http\Controllers\Api\V1\User\Auth\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|  I change the gured from api to user

|
*/

// User Routes
Route::prefix('v1/auth')->group(function () {


    // Authentication Routes
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('login', [UserAuthController::class, 'login']);

    // Protected Routes
    Route::middleware(['auth:user'])->group(function () {

        Route::post('refresh', [UserAuthController::class, 'refresh']);
        Route::post('logout', [UserAuthController::class, 'logout']);

        Route::get('profile', [UserProfileController::class, 'show_profile']);
        // ملاحظات مهمة
        // HTTP Method: استخدمنا POST لأن المستخدم قد يرسل بيانات + صورة
        Route::post('update-profile', [UserProfileController::class, 'update_profile']);
        Route::put('change-password', [UserProfileController::class, 'change_Password']);
        Route::delete('profile', [UserProfileController::class, 'delete_account']);
    });
});



Route::prefix('v1/auth')->group(function () {
    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::post('login', [AdminAuthController::class, 'login']);


        // Apply Middleware Alias for Admin Routes
        Route::middleware(['CheckIsAdmin'])->group(function () {

            Route::middleware(['auth:admin'])->group(function () {
                Route::post('refresh', [AdminAuthController::class, 'refresh']);
                Route::post('logout', [AdminAuthController::class, 'logout']);

                Route::get('profile', [AdminProfileController::class, 'show_profile']);
                Route::post('update-profile', [AdminProfileController::class, 'update_profile']);
                Route::put('change-password', [AdminProfileController::class, 'change_password']);
                Route::delete('profile', [AdminProfileController::class, 'delete_account']);
            });
        });
    });
});


// =====================
//  Admin Mange Users Route
// =====================

Route::prefix('v1')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::middleware(['auth:admin', 'CheckIsAdmin'])->group(function () {
            Route::prefix('users')->group(function () {
                Route::get('/', [UserManagementController::class, 'index']);
                Route::get('/{user}', [UserManagementController::class, 'show']);
                Route::put('/{user}', [UserManagementController::class, 'update']);
                Route::delete('/{user}', [UserManagementController::class, 'destroy']);
            });
        });
    });
});

// Test Route
Route::get('test', function () {
    return response()->json(['message' => 'API connected successfully']);
});
