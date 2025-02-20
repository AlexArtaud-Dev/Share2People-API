<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CreateUserRequestDTO;
use App\Application\DTOs\UserResponseDTO;
use App\Application\Services\EmailVerificationServiceInterface;
use App\Domain\Models\User;
use App\Domain\Repositories\UserRepositoryInterface;

class RegisterUserUseCase
{
    protected UserRepositoryInterface $userRepository;
    protected EmailVerificationServiceInterface $emailVerificationService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EmailVerificationServiceInterface $emailVerificationService
    ) {
        $this->userRepository = $userRepository;
        $this->emailVerificationService = $emailVerificationService;
    }

    public function execute(CreateUserRequestDTO $dto): UserResponseDTO
    {
        // Create a new domain user. (Note: The domain User model should not know about framework specifics.)
        $user = new User($dto->name, $dto->email, bcrypt($dto->password));
        $savedUser = $this->userRepository->save($user);

        // Trigger the email verification notification.
        $this->emailVerificationService->sendVerificationEmail($savedUser->getId());

        return new UserResponseDTO([
            'id'         => $savedUser->getId(),
            'name'       => $savedUser->getName(),
            'email'      => $savedUser->getEmail(),
            'created_at' => $savedUser->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $savedUser->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
