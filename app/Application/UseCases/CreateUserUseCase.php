<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CreateUserRequestDTO;
use App\Application\DTOs\UserResponseDTO;
use App\Domain\Models\User;
use App\Domain\Repositories\UserRepositoryInterface;

class CreateUserUseCase
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(CreateUserRequestDTO $dto): UserResponseDTO
    {
        // Optionally hash the password (if not already hashed)
        $hashedPassword = password_hash($dto->password, PASSWORD_BCRYPT);

        $user = new User($dto->name, $dto->email, $hashedPassword);
        $savedUser = $this->userRepository->save($user);

        return new UserResponseDTO([
            'id' => $savedUser->getId(),
            'name' => $savedUser->getName(),
            'email' => $savedUser->getEmail(),
            'created_at' => $savedUser->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $savedUser->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
