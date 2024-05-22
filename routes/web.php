<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProductDetailController;
use App\Http\Controllers\User\ProductListController;
use App\Http\Controllers\User\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [UserController::class, 'index'])->name('user.home');
Route::get('/contact', [UserController::class, 'contact'])->name('contact');


// User Routes
Route::get('/dashboard', [DashboardController::class, 'index']
)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('checkout')->controller(CheckoutController::class)->group(function () {
        Route::post('order', 'store')->name('checkout.store');
        Route::get('sucess', 'success')->name('checkout.success');
        
    });
});

//add to cart
Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('view', 'view')->name('cart.view');
    Route::post('store/{product}', 'store')->name('cart.store');
    Route::patch('update/{product}', 'update')->name('cart.update');
    Route::delete('delete/{product}', 'delete')->name('cart.delete');
});

// Admin Routes

Route::group(['prefix' => 'admin', 'middleware' => 'redirectAdmin'], function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

});


Route::middleware(['auth', 'admin'])->prefix(('admin'))->group(function() {
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    //products route
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products/store', [ProductController::class, 'store'])->name('admin.products.store');
    Route::put('/products/update/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/image/{id}', [ProductController::class, 'deleteImages'])->name('admin.products.image.delete');
    Route::delete('/product/destroy/{id}', [ProductController::class, 'destroy'])->name('admin.products.delete');


    //brands route
    Route::get('/brands', [BrandController::class, 'index'])->name('admin.brands.index');
    Route::post('/brands/store', [BrandController::class, 'store'])->name('admin.brands.store');
    Route::patch('/brands/update/{id}', [BrandController::class, 'update'])->name(('admin.brands.update'));
    Route::delete('/brands/destroy/{id}', [BrandController::class, 'destroy'])->name('admin.brands.delete');
});

// roue for product list and filter
Route::prefix('products')->controller(ProductListController::class)->group(function() {
    Route::get('/', 'index')->name('products.index');
});

Route::get('/product', [ProductDetailController::class, 'index'])->name('product.single');



require __DIR__.'/auth.php';
