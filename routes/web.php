<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Dashboard
Route::get('/', [ProductController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);

// Product Management (Add, Edit, Delete)
Route::get('/products/create', [ProductController::class, 'create']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}/edit', [ProductController::class, 'edit']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

// Selling
Route::put('/products/{id}/sell', [ProductController::class, 'sell']);