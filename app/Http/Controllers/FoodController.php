<?php

namespace App\Http\Controllers;

use App\Services\FoodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function __construct(private FoodService $foodService) {}

    public function index(): JsonResponse
    {
        return response()->json($this->foodService->getAll());
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->foodService->getById($id));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'quantidade' => 'required|integer|min:1',
            'data_validade' => 'required|date',
        ]);

        $food = $this->foodService->save($request->all());
        return response()->json($food, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return response()->json($this->foodService->update($id, $request->all()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->foodService->delete($id);
        return response()->json(null, 204);
    }
}