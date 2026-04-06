<?php

namespace App\Services;

use App\Models\Food;

class FoodService
{
    public function getAll(): array
    {
        return Food::all()->toArray();
    }

    public function getById(int $id): Food
    {
        $food = Food::find($id);

        if (!$food) {
            throw new \RuntimeException("Comida não encontrada com o ID: $id");
        }

        return $food;
    }

    public function save(array $data): Food
    {
        return Food::create($data);
    }

    public function update(int $id, array $data): Food
    {
        $food = $this->getById($id);
        $food->update($data);
        return $food;
    }

    public function delete(int $id): void
    {
        $this->getById($id)->delete();
    }
}