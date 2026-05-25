<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::prefix('v1')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('fridges', FridgeController::class);
    Route::post('fridges/{id}/products', [FridgeController::class, 'addProduct']);
    Route::get('fridges/{id}/products',  [FridgeController::class, 'products']);

    Route::get('products',          [ProductController::class, 'index']);
    Route::get('products/{id}',     [ProductController::class, 'show']);
    Route::put('products/{id}',     [ProductController::class, 'update']);
    Route::delete('products/{id}',  [ProductController::class, 'destroy']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('products', [ProductController::class, 'store']);
    });
});
