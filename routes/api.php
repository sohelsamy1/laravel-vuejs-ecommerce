<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Brand
Route::get('/BrandList', [BrandController::class, 'BrandList']);

// Category
Route::get('/CategoryList', [CategoryController::class, 'CategoryList']);
