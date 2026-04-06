<?php

namespace App\Http\Controllers;

use App\Services\GeladeiraService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeladeiraController extends Controller
{
    public function __construct(private GeladeiraService $geladeiraService) {}

    public function index(): JsonResponse
    {
        return response()->json($this->geladeiraService->getAll());
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->geladeiraService->getById($id));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $geladeira = $this->geladeiraService->save($request->all());
        return response()->json($geladeira, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return response()->json($this->geladeiraService->update($id, $request->all()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->geladeiraService->delete($id);
        return response()->json(null, 204);
    }

    public function produtos(int $id): JsonResponse
    {
        return response()->json($this->geladeiraService->getProdutos($id));
    }

    public function adicionarProduto(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'produto_id' => 'required|integer|exists:food_table,id',
            'quantidade' => 'required|integer|min:1',
        ]);

        $produto = $this->geladeiraService->adicionarProduto($id, $request->all());
        return response()->json($produto, 201);
    }
}