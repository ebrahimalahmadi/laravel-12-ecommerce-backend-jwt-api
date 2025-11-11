<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|
*/

// Test Route
Route::get('test', function () {
    return response()->json(['message' => 'API connected successfully']);
});
