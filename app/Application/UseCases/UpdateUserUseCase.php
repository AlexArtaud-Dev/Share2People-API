<?php


namespace App\Application\UseCases;

use App\Application\DTOs\UserResponseDTO;
use App\Domain\Repositories\UserRepositoryInterface;
use Exception;

class UpdateUserUseCase
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(int $id, array $data): UserResponseDTO
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new Exception("User not found.");
        }
        if (isset($data['name'])) {
            $user->setName($data['name']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['password'])) {
            $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        }
        $updatedUser = $this->userRepository->save($user);

        return new UserResponseDTO([
            'id' => $updatedUser->getId(),
            'name' => $updatedUser->getName(),
            'email' => $updatedUser->getEmail(),
            'created_at' => $updatedUser->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $updatedUser->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
