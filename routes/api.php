<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Rotas publicas
Route::post('login', [AuthController::class, 'login']);

Route::prefix('v1')->group(function () {
    // Rotas de consulta pública
    Route::get('products',          [ProductController::class, 'index']);
    Route::get('products/{id}',     [ProductController::class, 'show']);

    // Rotas protegidas pelo sanctum
    Route::middleware('auth:sanctum')->group(function () {

        // Gerenciamento de usuários e geladeiras
        Route::apiResource('users', UserController::class);
        Route::apiResource('fridges', FridgeController::class);

        // Ações dentro da geladeira do usuário logado
        Route::post('fridges/{id}/products', [FridgeController::class, 'addProduct']);
        Route::get('fridges/{id}/products',  [FridgeController::class, 'products']);

        // Cadastro/edição do catálogo global
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::delete('products/{id}', [ProductController::class, 'destroy']);
    });
});
);
