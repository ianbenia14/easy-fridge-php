<?php

namespace App\Http\Controllers;

use App\Services\GeladeiraProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeladeiraProductController extends Controller
{
    public function __construct(private GeladeiraProductService $geladeiraProductService) {}

    public function index(): JsonResponse
    {
        return response()->json($this->geladeiraProductService->getAll());
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->geladeiraProductService->getById($id));
    }

    public function store(Request $request): JsonResponse
    {
        $item = $this->geladeiraProductService->save($request->all());
        return response()->json($item, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return response()->json($this->geladeiraProductService->update($id, $request->all()));
    }

    public function partialUpdate(Request $request, int $id): JsonResponse
    {
        return response()->json($this->geladeiraProductService->partialUpdate($id, $request->all()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->geladeiraProductService->delete($id);
        return response()->json(null, 204);
    }
}