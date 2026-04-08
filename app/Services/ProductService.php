<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getAll(): array
    {
        return Product::all()->toArray();
    }

    public function getById(int $id): Product
    {
        $product = Product::find($id);

        if (!$product) {
            throw new \RuntimeException("Produto não encontrado com o ID: $id");
        }

        return $product;
    }

    public function save(array $data): Product
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->getById($id);
        $product->update($data);
        return $product;
    }

    public function delete(int $id): void
    {
        $this->getById($id)->delete();
    }
}