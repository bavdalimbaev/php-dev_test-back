<?php

use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\User\UserController;
use App\Utils\Tables\ETables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource(ETables::USER->value, UserController::class);
Route::apiResource(ETables::PRODUCT->value, ProductController::class);
Route::apiResource(ETables::CATEGORY->value, CategoryController::class);




