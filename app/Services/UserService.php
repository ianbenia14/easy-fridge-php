<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getAll(): array
    {
        return User::all()->toArray();
    }

    public function getById(int $id): User
    {
        $user = User::find($id);

        if (!$user) {
            throw new \RuntimeException("Usuário não encontrado com o ID: $id");
        }

        return $user;
    }

    public function save(array $data): User
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->save();

        return $user;
    }

    public function update(int $id, array $data): User
    {
        $user = $this->getById($id);
        $user->update($data);
        return $user;
    }

    public function delete(int $id): void
    {
        $this->getById($id)->delete();
    }
}
