<?php

namespace App\Domain\Repositories;

use App\Domain\Models\User;

interface UserRepositoryInterface
{
    public function save(User $user): User;

    public function find(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function delete(int $id): void;
}
