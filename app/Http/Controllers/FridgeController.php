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
        return response()->json($this->fridgeService->getAll());
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->fridgeService->getById($id));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $fridge = $this->fridgeService->save($request->all());
        return response()->json($fridge, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return response()->json($this->fridgeService->update($id, $request->all()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->fridgeService->delete($id);
        return response()->json(null, 204);
    }

    public function products(int $id): JsonResponse
    {
        return response()->json($this->fridgeService->getProducts($id));
    }

    public function addProduct(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantidade' => 'required|integer|min:1',
        ]);

        $product = $this->fridgeService->addProduct($id, $request->all());
        return response()->json($product, 201);
    }
}