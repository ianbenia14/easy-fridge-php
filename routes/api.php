<?php

use App\Http\Controllers\FoodController;
use App\Http\Controllers\GeladeiraController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::apiResource('foods', FoodController::class);
Route::apiResource('geladeiras', GeladeiraController::class);
Route::post('geladeiras/{id}/produtos', [GeladeiraController::class, 'adicionarProduto']);
Route::get('geladeiras/{id}/produtos', [GeladeiraController::class, 'produtos']);