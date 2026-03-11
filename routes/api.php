<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PolicyController;
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

// Policy
Route::get('/PolicyByType/{type}', [PolicyController::class, 'PolicyByType']);

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
    Route::POST('/CreateWishList/{product_id}', [ProductController::class, 'CreateWishList']);
    Route::get('/ProductWishList', [ProductController::class, 'ProductWishList']);
    Route::DELETE('/RemoveWishList/{product_id}', [ProductController::class, 'RemoveWishList']);

    // Cart
    Route::post('/CreateCartList', [ProductController::class, 'CreateCartList']);
    Route::get('/CartList', [ProductController::class, 'CartList']);
    Route::DELETE('/DeleteCartList/{product_id}', [ProductController::class, 'DeleteCartList']);

    // Invoice
    Route::POST('/InvoiceCreate', [InvoiceController::class, 'InvoiceCreate']);
    Route::get('/InvoiceList', [InvoiceController::class, 'InvoiceList']);
    Route::get('/InvoiceProductList/{invoice_id}', [InvoiceController::class, 'InvoiceProductList']);

});

Route::get('/PaymentSuccess', [InvoiceController::class, 'PaymentSuccess']);
Route::get('/PaymentCancel', [InvoiceController::class, 'PaymentCancel']);
Route::get('/PaymentFail', [InvoiceController::class, 'PaymentFail']);
Route::post('/PaymentIPN', [InvoiceController::class, 'PaymentIPN']);
