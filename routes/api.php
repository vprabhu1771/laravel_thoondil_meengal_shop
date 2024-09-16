<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

use App\Http\Controllers\api\v2\AuthController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', [AuthController::class, 'getUser']);
    
    Route::post('/logout', [AuthController::class, 'logout']);

});

use App\Http\Controllers\api\v2\ProductController;

Route::get('/products', [ProductController::class, 'index']);

use App\Http\Controllers\api\v2\OrderController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
});