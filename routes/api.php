<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|
*/


// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']); // Login user
    Route::post('register', [AuthController::class, 'register']);  // Register a new user


    // only if I'm authenticated with JWT token Authorization Headers
    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']); // Get authenticated user
        Route::post('logout', [AuthController::class, 'logout']); // Logout user
        Route::post('refresh', [AuthController::class, 'refresh']); // Refresh JWT token
    });
});





// Test Route
Route::get('test', function () {
    return response()->json(['message' => 'API connected successfully']);
});
