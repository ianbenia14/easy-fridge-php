<?php

namespace App\Services;

use App\Models\Fridge;
use App\Models\FridgeProduct;
use App\Models\User;
use App\Mail\ProductRemovedMail;
use Illuminate\Support\Facades\Mail;

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
    $fridge = $this->getById($fridgeId);

    $entry = FridgeProduct::create([
        'fridge_id'  => $fridgeId,
        'product_id' => $data['product_id'],
        'quantity'   => $data['quantity'],
    ]);

    try {
        $user = User::find($fridge->user_id);
        if ($user) {
            $product = $entry->product;
            Mail::to($user->email)->send(
                new ProductRemovedMail($product->name, $data['quantity'])
            );
        }
    } catch (\Exception $e) {
        \Log::error('Erro ao enviar e-mail: ' . $e->getMessage());
    }

    return $entry;
}
}
