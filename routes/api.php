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



// ----------------------
// User Authentication & Profile
// ----------------------
Route::prefix('v1/auth')->name('api.v1.auth.')->group(function () {

    // Public Routes
    Route::post('register', [UserAuthController::class, 'register'])->name('register');
    Route::post('login', [UserAuthController::class, 'login'])->name('login');

    // Protected Routes (User)
    Route::middleware(['auth:user'])->group(function () {

        Route::post('refresh', [UserAuthController::class, 'refresh'])->name('refresh');
        Route::post('logout', [UserAuthController::class, 'logout'])->name('logout');

        // Profile
        Route::get('profile', [UserProfileController::class, 'show_profile'])->name('profile.show');
        Route::post('update-profile', [UserProfileController::class, 'update_profile'])->name('profile.update');
        Route::put('change-password', [UserProfileController::class, 'change_password'])->name('profile.password.update');
        Route::delete('profile', [UserProfileController::class, 'delete_account'])->name('profile.delete');
    });
});

// ----------------------
// Admin Authentication & Profile
// ----------------------
Route::prefix('v1/auth/admin')->name('api.v1.admin.auth.')->group(function () {

    // Public Admin Login
    Route::post('login', action: [AdminAuthController::class, 'login'])->name('login');

    // Protected Admin Routes
    Route::middleware(['auth:admin', 'CheckIsAdmin'])->group(function () {
        Route::post('refresh', [AdminAuthController::class, 'refresh'])->name('refresh');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Profile
        Route::get('profile', [AdminProfileController::class, 'show_profile'])->name('profile.show');
        Route::post('update-profile', [AdminProfileController::class, 'update_profile'])->name('profile.update');
        Route::put('change-password', [AdminProfileController::class, 'change_password'])->name('profile.password.update');
        Route::delete('profile', [AdminProfileController::class, 'delete_account'])->name('profile.delete');
    });
});

// ----------------------
// Admin Manage Users
// ----------------------
Route::prefix('v1/admin')->name('api.v1.admin.')->middleware(['auth:admin', 'CheckIsAdmin'])->group(function () {

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    });
});

// ----------------------
// Test Route
// ----------------------
Route::get('test', function () {
    return response()->json(['message' => 'API connected successfully']);
})->name('api.test');
