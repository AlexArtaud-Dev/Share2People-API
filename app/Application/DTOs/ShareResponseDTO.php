<?php

namespace App\Application\DTOs;

class ShareResponseDTO
{
    public int $id;
    public string $title;
    public ?string $description;
    public string $contentType;
    public ?string $content;
    public ?string $fileUrl;
    public ?string $shortCode;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->contentType = $data['content_type'];
        $this->content = $data['content'] ?? null;
        $this->fileUrl = $data['file_url'] ?? null;
        $this->shortCode = $data['short_code'] ?? null;
        $this->createdAt = $data['created_at'];
        $this->updatedAt = $data['updated_at'];
    }
}
