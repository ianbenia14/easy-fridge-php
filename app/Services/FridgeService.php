<?php

namespace App\Services;

use App\Models\Fridge;
use App\Models\FridgeProduct;

class FridgeService
{
    public function getAll(): array
    {
        return Fridge::all()->toArray();
    }

    public function getById(int $id): Fridge
    {
        $fridge = Fridge::find($id);

        if (!$fridge) {
            throw new \RuntimeException("Fridge não encontrada com o ID: $id");
        }

        return $fridge;
    }

    public function save(array $data): Fridge
    {
        return Fridge::create($data);
    }

    public function update(int $id, array $data): Fridge
    {
        $fridge = $this->getById($id);
        $fridge->update($data);
        return $fridge;
    }

    public function delete(int $id): void
    {
        $this->getById($id)->delete();
    }

    public function getProducts(int $id): array
    {
        return $this->getById($id)->products->toArray();
    }

    public function addProduct(int $fridgeId, array $data): FridgeProduct
    {
        $this->getById($fridgeId);

        return FridgeProduct::create([
            'fridge_id'  => $fridgeId,
            'product_id' => $data['product_id'],
            'quantidade' => $data['quantidade'],
        ]);
    }
}