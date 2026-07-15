<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ToppingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('pos.index') : redirect()->route('login');
});

Route::get('/system/migrate', [SystemController::class, 'migrate'])->name('system.migrate');
Route::get('/system/seed', [SystemController::class, 'seed'])->name('system.seed');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::middleware('role:cashier|admin')->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/orders', [PosController::class, 'store'])->name('pos.orders.store');
        Route::get('/pos/history', [PosController::class, 'history'])->name('pos.history');
        Route::put('/pos/orders/{order}/pay', [PosController::class, 'pay'])->name('pos.orders.pay');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::middleware('permission:manage products')->group(function () {
            Route::put('/products/{product}/image', [ProductController::class, 'updateImage'])->name('products.image');
            Route::resource('products', ProductController::class)->except(['show']);
            Route::resource('variants', ProductVariantController::class)->except(['show']);
            Route::resource('toppings', ToppingController::class)->except(['show']);
        });

        Route::middleware('permission:manage users')->group(function () {
            Route::resource('users', UserController::class)->except(['show']);
        });

        Route::middleware('permission:view reports')->group(function () {
            Route::get('/analytics/sales', [AnalyticsController::class, 'sales'])->name('analytics.sales');
            Route::get('/analytics/top-products', [AnalyticsController::class, 'topProducts'])->name('analytics.top-products');
        });
    });
});
