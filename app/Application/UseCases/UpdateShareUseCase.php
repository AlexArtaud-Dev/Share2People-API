<?php


namespace App\Application\UseCases;

use App\Application\DTOs\UpdateShareRequestDTO;
use App\Application\DTOs\ShareResponseDTO;
use App\Domain\Repositories\ShareRepositoryInterface;
use Exception;

class UpdateShareUseCase
{
    protected ShareRepositoryInterface $shareRepository;

    public function __construct(ShareRepositoryInterface $shareRepository)
    {
        $this->shareRepository = $shareRepository;
    }

    public function execute(UpdateShareRequestDTO $dto): ShareResponseDTO
    {
        $share = $this->shareRepository->find($dto->id);
        if (!$share) {
            throw new Exception("Share not found.");
        }
        $share->setTitle($dto->title);
        $share->setDescription($dto->description);
        $share->setContentType($dto->contentType);
        $share->setContent($dto->content);
        $share->setFileUrl($dto->fileUrl);
        $share->setShortCode($dto->shortCode);
        $share->updateTimestamps();

        $updatedShare = $this->shareRepository->save($share);
        return new ShareResponseDTO([
            'id' => $updatedShare->getId(),
            'title' => $updatedShare->getTitle(),
            'description' => $updatedShare->getDescription(),
            'content_type' => $updatedShare->getContentType(),
            'content' => $updatedShare->getContent(),
            'file_url' => $updatedShare->getFileUrl(),
            'short_code' => $updatedShare->getShortCode(),
            'created_at' => $updatedShare->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $updatedShare->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
