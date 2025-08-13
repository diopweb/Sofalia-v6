<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PaymentController;

Route::apiResource('products', ProductController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('sales', SaleController::class);
Route::post('sales/{sale}/pay', [SaleController::class, 'addPayment']);
Route::apiResource('payments', PaymentController::class);
