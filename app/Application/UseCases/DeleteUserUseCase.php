<?php


namespace App\Application\UseCases;

use App\Domain\Repositories\UserRepositoryInterface;
use Exception;

class DeleteUserUseCase
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(int $id): void
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new Exception("User not found.");
        }
        $this->userRepository->delete($id);
    }
}
