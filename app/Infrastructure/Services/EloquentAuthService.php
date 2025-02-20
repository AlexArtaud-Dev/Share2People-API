<?php


namespace App\Infrastructure\Services;

use App\Application\Services\AuthServiceInterface;
use App\Domain\Models\User;
use App\Infrastructure\Models\EloquentUser;

class EloquentAuthService implements AuthServiceInterface
{
    public function createToken(User $user): string
    {
        $eloquentUser = EloquentUser::find($user->getId());
        if (!$eloquentUser) {
            throw new \Exception("User not found in Eloquent.");
        }
        return $eloquentUser->createToken('api-token')->plainTextToken;
    }
}
