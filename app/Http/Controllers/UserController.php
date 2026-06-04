<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function index(): JsonResponse
    {
        return response()->json($this->userService->getAll());
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->userService->getById($id));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = $this->userService->save($request->all());
        return response()->json($user, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return response()->json($this->userService->update($id, $request->all()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->userService->delete($id);
        return response()->json(null, 204);
    }
}