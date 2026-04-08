<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('fridges', FridgeController::class);
Route::post('fridges/{id}/products', [FridgeController::class, 'addProduct']);
Route::get('fridges/{id}/products', [FridgeController::class, 'products']);