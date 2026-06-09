<?php

namespace App\Services;

use App\Models\Fridge;
use App\Models\FridgeProduct;
use App\Models\FridgeMovement;
use App\Models\User;

class FridgeService
{
    // RETORNA APENAS AS GELADEIRAS DO USUÁRIO LOGADO
    public function getAllByUserId(int $userId): array
    {
        return Fridge::where('user_id', $userId)->get()->toArray();
    }

    // BUSCA A GELADEIRA GARANTINDO QUE ELA PERTENCE AO USUÁRIO
    public function getByIdAndUser(int $id, int $userId): ?Fridge
    {
        return Fridge::where('id', $id)->where('user_id', $userId)->first();
    }

    // Mantido original (o ID do usuário já vem injetado pelo Controller de forma segura)
    public function save(array $data): Fridge
    {
        return Fridge::create($data);
    }

    // ATUALIZAÇÃO SEGURA
    public function updateSecure(int $id, int $userId, array $data): ?Fridge
    {
        $fridge = $this->getByIdAndUser($id, $userId);

        if (!$fridge) {
            return null; // Controller vai responder 403
        }

        $fridge->update($data);
        return $fridge;
    }

    // DELEÇÃO SEGURA
    public function deleteSecure(int $id, int $userId): bool
    {
        $fridge = $this->getByIdAndUser($id, $userId);

        if (!$fridge) {
            return false;
        }

        $fridge->delete();
        return true;
    }

    // LISTAR PRODUTOS DE FORMA SEGURA
    public function getProductsSecure(int $id, int $userId): ?array
    {
        $fridge = $this->getByIdAndUser($id, $userId);

        if (!$fridge) {
            return null;
        }

        return $fridge->products->toArray();
    }

    // ADICIONAR PRODUTO DE FORMA SEGURA
    public function addProductSecure(int $fridgeId, int $userId, array $data): ?FridgeProduct
    {
        // Valida se a geladeira existe e pertence ao usuário logado antes de fazer qualquer coisa
        $fridge = $this->getByIdAndUser($fridgeId, $userId);

        if (!$fridge) {
            return null; // Bloqueia a inserção se não for o dono
        }

        $entry = FridgeProduct::create([
            'fridge_id'       => $fridgeId,
            'product_id'      => $data['product_id'],
            'quantity'        => $data['quantity'],
            'expiration_date' => $data['expiration_date'] ?? null,
        ]);

        FridgeMovement::create([
            'user_id'    => $userId,
            'fridge_id'  => $fridgeId,
            'product_id' => $data['product_id'],
            'action'     => 'added',
            'quantity'   => $data['quantity'],
        ]);

        return $entry;
    }

    // BUSCA MOVIMENTAÇÕES DO USUÁRIO POR PERÍODO
    public function getMovementsByUser(int $userId, string $period): array
    {
        $from = match($period) {
            'daily'   => now()->startOfDay(),
            'monthly' => now()->startOfMonth(),
            default   => now()->startOfDay(),
        };

        return FridgeMovement::with('product')
            ->where('user_id', $userId)
            ->where('created_at', '>=', $from)
            ->get()
            ->map(fn($m) => [
                'product_name' => $m->product->name,
                'action'       => $m->action,
                'quantity'     => $m->quantity,
                'created_at'   => $m->created_at->format('d/m/Y H:i'),
            ])
            ->toArray();
    }

    // REMOVER PRODUTO DA GELADEIRA DE FORMA SEGURA
    public function removeProductSecure(int $fridgeId, int $userId, int $productId): bool
    {
        $fridge = $this->getByIdAndUser($fridgeId, $userId);

        if (!$fridge) {
            return false;
        }

        FridgeProduct::where('fridge_id', $fridgeId)
                     ->where('product_id', $productId)
                     ->delete();

        // Registra a movimentação
        FridgeMovement::create([
            'user_id'    => $userId,
            'fridge_id'  => $fridgeId,
            'product_id' => $productId,
            'action'     => 'removed',
            'quantity'   => 0,
        ]);

        return true;
    }
}