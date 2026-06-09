<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

// Rotas 100% Públicas (fora do prefix v1)
Route::post('login', [AuthController::class, 'login']);

Route::prefix('v1')->group(function () {
    // Rotas de consulta pública
    Route::get('products',          [ProductController::class, 'index']);
    Route::get('products/{id}',     [ProductController::class, 'show']);
    Route::post('users',            [UserController::class, 'store']); // registro público

    // TODO O RESTO EXIGE LOGIN (Protegido pelo Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        // Gerenciamento de Usuários e Geladeiras
        Route::apiResource('users', UserController::class)->except(['store']);
        Route::apiResource('fridges', FridgeController::class);

        // Ações dentro da geladeira do usuário logado
        Route::post('fridges/{id}/products', [FridgeController::class, 'addProduct']);
        Route::get('fridges/{id}/products',  [FridgeController::class, 'products']);

        // Cadastro/Edição do catálogo global
        Route::post('products',         [ProductController::class, 'store']);
        Route::put('products/{id}',     [ProductController::class, 'update']);
        Route::delete('products/{id}',  [ProductController::class, 'destroy']);

        // Emails
        Route::post('email/movement-report',    [EmailController::class, 'sendMovementReport']);
        Route::post('email/expiring-products',  [EmailController::class, 'sendExpiringProducts']);
    });
});
