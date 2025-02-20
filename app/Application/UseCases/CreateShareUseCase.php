<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CreateShareRequestDTO;
use App\Application\DTOs\ShareResponseDTO;
use App\Domain\Models\Share;
use App\Domain\Repositories\ShareRepositoryInterface;

class CreateShareUseCase
{
    protected ShareRepositoryInterface $shareRepository;

    public function __construct(ShareRepositoryInterface $shareRepository)
    {
        $this->shareRepository = $shareRepository;
    }

    public function execute(CreateShareRequestDTO $dto): ShareResponseDTO
    {
        $share = new Share(
            $dto->userId,
            $dto->title,
            $dto->description,
            $dto->contentType,
            $dto->content,
            $dto->fileUrl,
            $dto->shortCode
        );
        $savedShare = $this->shareRepository->save($share);

        return new ShareResponseDTO([
            'id' => $savedShare->getId(),
            'title' => $savedShare->getTitle(),
            'description' => $savedShare->getDescription(),
            'content_type' => $savedShare->getContentType(),
            'content' => $savedShare->getContent(),
            'file_url' => $savedShare->getFileUrl(),
            'short_code' => $savedShare->getShortCode(),
            'created_at' => $savedShare->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $savedShare->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
