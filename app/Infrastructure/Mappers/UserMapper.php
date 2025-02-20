<?php

namespace App\Infrastructure\Mappers;

use App\Domain\Models\User;
use App\Infrastructure\Models\EloquentUser;

class UserMapper
{
    public static function toDomain(EloquentUser $eloquentUser): User
    {
        $user = new User($eloquentUser->name, $eloquentUser->email, $eloquentUser->password);
        $user->setId($eloquentUser->id);
        $user->setCreatedAt(new \DateTime($eloquentUser->created_at));
        $user->setEmailVerifiedAt(new \DateTime($eloquentUser->email_verified_at));
        $user->setUpdatedAt(new \DateTime($eloquentUser->updated_at));
        return $user;
    }
}
