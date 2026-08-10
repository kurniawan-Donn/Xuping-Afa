<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [WebsiteController::class, 'productIndex'])->name('website.products.index');

Route::middleware('guest')->prefix('login')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/', [AuthController::class, 'processLogin'])->name('login.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('products', ProductController::class);
    
    Route::post('/product-images/{image}/set-primary', [ProductController::class, 'setPrimaryImage'])->name('product-images.set-primary');
    Route::delete('/product-images/{image}', [ProductController::class, 'deleteImage'])->name('product-images.destroy');

    Route::middleware(['role:owner|superadmin'])->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
