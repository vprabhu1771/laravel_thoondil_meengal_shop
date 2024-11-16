<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ReceiptController;

Route::get('/receipt/{id}', [ReceiptController::class, 'printReceipt'])->name('receipt.print');
