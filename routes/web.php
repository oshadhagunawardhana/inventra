<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Wso2AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::middleware(['guest', 'throttle:20,1'])->group(function () {
    Route::get('/auth/wso2', [Wso2AuthController::class, 'redirect'])->name('wso2.login');
    Route::get('/auth/wso2/callback', [Wso2AuthController::class, 'callback'])->name('wso2.callback');
});

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])
    ->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('categories', CategoryController::class);

    Route::resource('products', ProductController::class);

    Route::resource('customers', CustomerController::class);

    Route::resource('sales', SaleController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);

    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');
});
