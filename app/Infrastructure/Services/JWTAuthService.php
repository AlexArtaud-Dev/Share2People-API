<?php

namespace App\Infrastructure\Services;

use App\Application\Services\AuthServiceInterface;
use App\Domain\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Infrastructure\Models\EloquentUser;

class JWTAuthService implements AuthServiceInterface
{
    public function createToken(User $user): string
    {
        // Retrieve the EloquentUser corresponding to the domain user.
        $eloquentUser = EloquentUser::find($user->getId());
        if (!$eloquentUser) {
            throw new \Exception("EloquentUser not found for domain user ID {$user->getId()}");
        }
        return JWTAuth::fromUser($eloquentUser);
    }

    public function refreshToken(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }
}
