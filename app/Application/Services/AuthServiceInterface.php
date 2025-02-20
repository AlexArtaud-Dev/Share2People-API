<?php


namespace App\Application\Services;

use App\Domain\Models\User;

interface AuthServiceInterface
{
    public function createToken(User $user): string;
}
