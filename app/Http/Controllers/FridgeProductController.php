<?php

namespace App\Http\Controllers;

use App\Services\FridgeProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FridgeProductController extends Controller
{
    public function __construct(private FridgeProductService $fridgeProductService) {}

    public function index(): JsonResponse
    {
        return response()->json($this->fridgeProductService->getAll());
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->fridgeProductService->getById($id));
    }

    public function store(Request $request): JsonResponse
    {
        $item = $this->fridgeProductService->save($request->all());
        return response()->json($item, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return response()->json($this->fridgeProductService->update($id, $request->all()));
    }

    public function partialUpdate(Request $request, int $id): JsonResponse
    {
        return response()->json($this->fridgeProductService->partialUpdate($id, $request->all()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->fridgeProductService->delete($id);
        return response()->json(null, 204);
    }
}