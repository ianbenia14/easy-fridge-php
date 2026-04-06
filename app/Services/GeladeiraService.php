<?php

namespace App\Services;

use App\Models\Geladeira;
use App\Models\GeladeiraProduct;

class GeladeiraService
{
    public function getAll(): array
    {
        return Geladeira::all()->toArray();
    }

    public function getById(int $id): Geladeira
    {
        $geladeira = Geladeira::find($id);

        if (!$geladeira) {
            throw new \RuntimeException("Geladeira não encontrada com o ID: $id");
        }

        return $geladeira;
    }

    public function save(array $data): Geladeira
    {
        return Geladeira::create($data);
    }

    public function update(int $id, array $data): Geladeira
    {
        $geladeira = $this->getById($id);
        $geladeira->update($data);
        return $geladeira;
    }

    public function delete(int $id): void
    {
        $this->getById($id)->delete();
    }

    public function getProdutos(int $id): array
    {
        return $this->getById($id)->produtos->toArray();
    }

    public function adicionarProduto(int $geladeiraId, array $data): GeladeiraProduct
    {
        $this->getById($geladeiraId);

        return GeladeiraProduct::create([
            'geladeira_id' => $geladeiraId,
            'produto_id' => $data['produto_id'],
            'quantidade' => $data['quantidade'],
        ]);
    }
}