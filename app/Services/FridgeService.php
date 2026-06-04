<?php

namespace App\Services;

use App\Models\Fridge;
use App\Models\FridgeProduct;
use App\Models\User;
<<<<<<< HEAD
use App\Mail\ProductRemovedMail; // Sugestão: Ajustar o nome do e-mail de acordo com a ação
=======
use App\Mail\ProductRemovedMail;
>>>>>>> 8d29fd5482700a7e1c275f86fce944dd4b94c509
use Illuminate\Support\Facades\Mail;

class FridgeService
{
<<<<<<< HEAD
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
=======
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

>>>>>>> 8d29fd5482700a7e1c275f86fce944dd4b94c509
    public function save(array $data): Fridge
    {
        return Fridge::create($data);
    }

<<<<<<< HEAD
    // ATUALIZAÇÃO SEGURA
    public function updateSecure(int $id, int $userId, array $data): ?Fridge
    {
        $fridge = $this->getByIdAndUser($id, $userId);

        if (!$fridge) {
            return null; // Controller vai responder 403
        }

=======
    public function update(int $id, array $data): Fridge
    {
        $fridge = $this->getById($id);
>>>>>>> 8d29fd5482700a7e1c275f86fce944dd4b94c509
        $fridge->update($data);
        return $fridge;
    }

<<<<<<< HEAD
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
            'fridge_id'  => $fridgeId,
            'product_id' => $data['product_id'],
            'quantity'   => $data['quantity'],
        ]);

        // Disparo do e-mail
        try {
            $user = User::find($userId); // id que já veio do Sanctum
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
=======
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
>>>>>>> 8d29fd5482700a7e1c275f86fce944dd4b94c509
}
