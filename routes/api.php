<?php

use App\Http\Controllers\Api\V1\User\Auth\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|  i change the gured from api to user

|
*/

// User Routes
Route::prefix('v1/auth')->group(function () {
    // Authentication Routes
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('login', [UserAuthController::class, 'login']);

    // Protected Routes
    Route::middleware(['auth:user'])->group(function () {
        //         PUT أو PATCH تستخدم لتحديث مورد موجود،
        // لكن في حالة refresh أو logout لا يحدث تحديث مباشر لجدول قاعدة
        // البيانات، بل هي عملية “action”، لذلك POST هو الأنسب.

        Route::post('refresh', [UserAuthController::class, 'refresh']);
        Route::post('logout', [UserAuthController::class, 'logout']);
    });
});


// Test Route
Route::get('test', function () {
    return response()->json(['message' => 'API connected successfully']);
});
