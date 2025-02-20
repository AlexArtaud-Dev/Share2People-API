<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Share;

interface ShareRepositoryInterface
{
    public function save(Share $share): Share;
    public function find(int $id): ?Share;
    public function getAll(): array;
    public function delete(int $id): void;
}
