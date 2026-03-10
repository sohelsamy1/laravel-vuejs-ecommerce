<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Brand
Route::get('/BrandList', [BrandController::class, 'BrandList']);

// Category
Route::get('/CategoryList', [CategoryController::class, 'CategoryList']);

// Product
Route::get('/ListProductByCategory/{id}', [ProductController::class, 'ListProductByCategory']);
Route::get('/ListProductByBrand/{id}', [ProductController::class, 'ListProductByBrand']);
Route::get('/ListProductByRemark/{remark}', [ProductController::class, 'ListProductByRemark']);
Route::get('/ListProductSlider', [ProductController::class, 'ListProductSlider']);
Route::get('/ProductDetailsById/{id}', [ProductController::class, 'ProductDetailsById']);
Route::get('/ListReviewByProduct/{product_id}', [ProductController::class, 'ListReviewByProduct']);

// Auth
Route::post('/UserLogin', [UserController::class, 'UserLogin']);
Route::post('/VerifyLogin', [UserController::class, 'VerifyLogin']);


// -------------------- Protected Routes --------------------
Route::middleware(['token.auth'])->group(function () {

    // Auth
    Route::post('/logout', [UserController::class, 'UserLogout']);

    // Profile
    Route::post('/CreateProfile', [ProfileController::class, 'CreateProfile']);
    Route::get('/ReadProfile', [ProfileController::class, 'ReadProfile']);

    // Wishlist
    Route::get('/CreateWishList/{product_id}', [ProductController::class, 'CreateWishList']);
    Route::get('/ProductWishList', [ProductController::class, 'ProductWishList']);
    Route::get('/RemoveWishList/{product_id}', [ProductController::class, 'RemoveWishList']);

    // Cart
    Route::post('/CreateCartList', [ProductController::class, 'CreateCartList']);
    Route::get('/CartList', [ProductController::class, 'CartList']);
    Route::get('/DeleteCartList/{product_id}', [ProductController::class, 'DeleteCartList']);

    // Invoice
    Route::get('/InvoiceCreate', [InvoiceController::class, 'InvoiceCreate']);
    Route::get('/InvoiceList', [InvoiceController::class, 'InvoiceList']);


});
