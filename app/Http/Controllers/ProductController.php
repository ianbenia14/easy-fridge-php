<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(): JsonResponse
    {
        return response()->json($this->productService->getAll());
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productService->getById($id));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'          => 'required|string',
            'quantidade'    => 'required|integer|min:1',
            'data_validade' => 'required|date',
        ]);

        $product = $this->productService->save($request->all());
        return response()->json($product, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return response()->json($this->productService->update($id, $request->all()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->productService->delete($id);
        return response()->json(null, 204);
    }
}