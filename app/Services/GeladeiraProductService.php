<?php

namespace App\Services;

use App\Models\GeladeiraProduct;

class GeladeiraProductService
{
    public function getAll(): array
    {
        return GeladeiraProduct::all()->toArray();
    }

    public function getById(int $id): GeladeiraProduct
    {
        $item = GeladeiraProduct::find($id);

        if (!$item) {
            throw new \RuntimeException("Item não encontrado com o ID: $id");
        }

        return $item;
    }

    public function save(array $data): GeladeiraProduct
    {
        return GeladeiraProduct::create($data);
    }

    public function update(int $id, array $data): GeladeiraProduct
    {
        $item = $this->getById($id);
        $item->update($data);
        return $item;
    }

    public function partialUpdate(int $id, array $data): GeladeiraProduct
    {
        $item = $this->getById($id);
        $item->update(array_filter($data));
        return $item;
    }

    public function delete(int $id): void
    {
        $this->getById($id)->delete();
    }
}