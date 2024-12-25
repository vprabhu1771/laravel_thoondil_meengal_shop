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

    Route::post('/order/confirm', [OrderController::class, 'store']);
    
});


use App\Http\Controllers\api\v2\CartController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/carts', [CartController::class, 'index']);
    Route::post('/add-to-cart', [CartController::class, 'addToCart']);
    Route::post('/remove-from-cart', [CartController::class, 'removeFromCart']);
    Route::post('/increase-quantity', [CartController::class, 'increaseQuantity']);
    Route::post('/decrease-quantity', [CartController::class, 'decreaseQuantity']);
    Route::post('/clear-cart', [CartController::class, 'clearCart']);
});


use App\Http\Controllers\ReceiptController;

Route::get('/receipt/{id}', [ReceiptController::class, 'printReceipt'])->name('receipt.print');