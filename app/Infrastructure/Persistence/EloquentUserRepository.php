<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Models\EloquentUser;
use App\Infrastructure\Mappers\UserMapper;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function save(User $user): User
    {
        $eloquentUser = new EloquentUser();
        $eloquentUser->name = $user->getName();
        $eloquentUser->email = $user->getEmail();
        $eloquentUser->password = $user->getPassword();
        $eloquentUser->save();

        $user->setId($eloquentUser->id);
        return $user;
    }

    public function find(int $id): ?User
    {
        $eloquentUser = EloquentUser::find($id);
        return $eloquentUser ? UserMapper::toDomain($eloquentUser) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $eloquentUser = EloquentUser::where('email', $email)->first();
        return $eloquentUser ? UserMapper::toDomain($eloquentUser) : null;
    }

    public function delete(int $id): void
    {
        $eloquentUser = EloquentUser::find($id);
        if ($eloquentUser) {
            $eloquentUser->delete();
        }
    }
}
