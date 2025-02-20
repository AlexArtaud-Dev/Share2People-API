<?php


namespace App\Application\UseCases;

use App\Application\DTOs\ShareResponseDTO;
use App\Domain\Repositories\ShareRepositoryInterface;
use Exception;

class GetShareByIdUseCase
{
    protected ShareRepositoryInterface $shareRepository;

    public function __construct(ShareRepositoryInterface $shareRepository)
    {
        $this->shareRepository = $shareRepository;
    }

    public function execute(int $id): ShareResponseDTO
    {
        $share = $this->shareRepository->find($id);
        if (!$share) {
            throw new Exception("Share not found.");
        }
        return new ShareResponseDTO([
            'id' => $share->getId(),
            'title' => $share->getTitle(),
            'description' => $share->getDescription(),
            'content_type' => $share->getContentType(),
            'content' => $share->getContent(),
            'file_url' => $share->getFileUrl(),
            'short_code' => $share->getShortCode(),
            'created_at' => $share->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $share->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
