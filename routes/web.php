<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('sales', SaleController::class)
    ->only(['index', 'create', 'store', 'show', 'destroy']);
