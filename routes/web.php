<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ReceiptController;

Route::get('/receipt/{id}', [ReceiptController::class, 'printReceipt'])->name('receipt.print');

use App\Http\Controllers\MigrationController;

Route::get('/migrate-fresh', [MigrationController::class, 'runMigrationFresh']);

Route::get('/migrate', [MigrationController::class, 'runMigrations']);

Route::get('/db-seed', [MigrationController::class, 'runDbSeed']);