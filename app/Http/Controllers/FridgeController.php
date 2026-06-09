<?php

namespace App\Http\Controllers;

use App\Services\FridgeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FridgeController extends Controller
{
    public function __construct(private FridgeService $fridgeService) {}

    public function index(): JsonResponse
    {
        $userId = auth()->id();
        return response()->json($this->fridgeService->getAllByUserId($userId));
    }

    public function show(int $id): JsonResponse
    {
        // verifica se a geladeira realmente é do usuario logado, pra depois mostras os detalhes
        $fridge = $this->fridgeService->getByIdAndUser($id, auth()->id());

        if (!$fridge) {
            return response()->json(['message' => 'Geladeira não encontrada ou acesso negado'], 403);
        }

        return response()->json($fridge);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->all();
        // id atribido ao token do sanctum, geladeira sempre associada ao usuario
        $data['user_id'] = $request->user()->id;

        $fridge = $this->fridgeService->save($data);
        return response()->json($fridge, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        // garante que o usuário só edite a própria geladeira
        $userId = auth()->id();
        $updatedFridge = $this->fridgeService->updateSecure($id, $userId, $request->all());

        if (!$updatedFridge) {
            return response()->json(['message' => 'Operação não autorizada'], 403);
        }

        return response()->json($updatedFridge);
    }

    public function destroy(int $id): JsonResponse
    {
        // garante que o usuário só delete a própria geladeira
        $userId = auth()->id();
        $deleted = $this->fridgeService->deleteSecure($id, $userId);

        if (!$deleted) {
            return response()->json(['message' => 'Operação não autorizada'], 403);
        }

        return response()->json(null, 204);
    }

    public function products(int $id): JsonResponse
    {
        // Só lista os produtos se a geladeira for do usuário logado
        $products = $this->fridgeService->getProductsSecure($id, auth()->id());

        if (!$products) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        return response()->json($products);
    }

    public function addProduct(Request $request, int $id): JsonResponse
    {
    $request->validate([
        'product_id'      => 'required|integer|exists:products,id',
        'quantity'        => 'required|integer|min:1',
        'expiration_date' => 'required|date',
    ]);

    $userId = $request->user()->id;
    $product = $this->fridgeService->addProductSecure($id, $userId, $request->all());

    if (!$product) {
        return response()->json(['message' => 'Não foi possível adicionar o item. Verifique as permissões da geladeira.'], 403);
    }

    return response()->json($product, 201);
    }

    public function removeProduct(Request $request, int $fridgeId, int $productId): JsonResponse
    {
    $userId = auth()->id();
    $removed = $this->fridgeService->removeProductSecure($fridgeId, $userId, $productId);

    if (!$removed) {
        return response()->json(['message' => 'Operação não autorizada'], 403);
    }

    return response()->json(null, 204);
    }
}
