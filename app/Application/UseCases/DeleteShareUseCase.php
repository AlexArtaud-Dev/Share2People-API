<?php


namespace App\Application\UseCases;

use App\Domain\Repositories\ShareRepositoryInterface;
use Exception;

class DeleteShareUseCase
{
    protected ShareRepositoryInterface $shareRepository;

    public function __construct(ShareRepositoryInterface $shareRepository)
    {
        $this->shareRepository = $shareRepository;
    }

    public function execute(int $id): void
    {
        $share = $this->shareRepository->find($id);
        if (!$share) {
            throw new Exception("Share not found.");
        }
        $this->shareRepository->delete($id);
    }
}
