<?php

namespace App\Application\UseCases;

use App\Application\DTOs\LoginUserRequestDTO;
use App\Application\DTOs\LoginUserResponseDTO;
use App\Application\Services\AuthServiceInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use Exception;

class LoginUserUseCase
{
    protected UserRepositoryInterface $userRepository;
    protected AuthServiceInterface $authService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AuthServiceInterface    $authService
    )
    {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    /**
     * Attempt to log in a user.
     *
     * @param LoginUserRequestDTO $dto
     * @return LoginUserResponseDTO
     *
     * @throws Exception if the credentials are invalid or if the user's email is not verified.
     */
    public function execute(LoginUserRequestDTO $dto): LoginUserResponseDTO
    {
        $user = $this->userRepository->findByEmail($dto->email);
        if (!$user || !password_verify($dto->password, $user->getPassword())) {
            throw new Exception("Invalid credentials.", 401);
        }

        // Check if the user's email has been verified.
        if (!method_exists($user, 'hasVerifiedEmail') || !$user->hasVerifiedEmail()) {
            throw new Exception("Email not verified.", 403);
        }

        $token = $this->authService->createToken($user);

        return new LoginUserResponseDTO([
            'token' => $token,
            'user_id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ]);
    }
}
