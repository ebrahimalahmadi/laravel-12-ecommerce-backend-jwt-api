<?php

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


// Test Route
Route::get('test', function () {
    return response()->json(['message' => 'API connected successfully']);
});
