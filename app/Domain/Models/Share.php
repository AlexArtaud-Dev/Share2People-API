<?php

namespace App\Domain\Models;

class Share
{
    private ?int $id;
    private int $userId;
    private string $title;
    private ?string $description;
    private string $contentType;
    private ?string $content;
    private ?string $fileUrl;
    private ?string $shortCode;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function __construct(
        int     $userId,
        string  $title,
        ?string $description,
        string  $contentType,
        ?string $content = null,
        ?string $fileUrl = null,
        ?string $shortCode = null
    )
    {
        $this->id = null;
        $this->userId = $userId;
        $this->title = $title;
        $this->description = $description;
        $this->contentType = $contentType;
        $this->content = $content;
        $this->fileUrl = $fileUrl;
        $this->shortCode = $shortCode;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getFileUrl(): ?string
    {
        return $this->fileUrl;
    }

    public function getShortCode(): ?string
    {
        return $this->shortCode;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    // Setters (for update use case)
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function setFileUrl(?string $fileUrl): void
    {
        $this->fileUrl = $fileUrl;
    }

    public function setShortCode(?string $shortCode): void
    {
        $this->shortCode = $shortCode;
    }

    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
