<?php

use App\Http\Controllers\Category\ActionController as CategoryActionController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ActionController as ProductActionController;
use App\Http\Controllers\User\UserController;
use App\Utils\Tables\ETables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource(ETables::USER->value, UserController::class);
Route::apiResource(ETables::PRODUCT->value, ProductController::class);
Route::apiResource(ETables::CATEGORY->value, CategoryController::class);


Route::prefix('categories')
    ->name('categories.')
    ->group(function () {
        Route::post('/{id}/products', [CategoryActionController::class, 'products'])->name('products');
    });


Route::prefix('products')
    ->name('products.')
    ->group(function () {
        Route::post('/{id}/categories', [ProductActionController::class, 'categories'])->name('categories');
    });


