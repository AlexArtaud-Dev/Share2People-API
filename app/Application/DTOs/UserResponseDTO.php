<?php

namespace App\Application\DTOs;

class UserResponseDTO
{
    public int $id;
    public string $name;
    public string $email;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->createdAt = $data['created_at'];
        $this->updatedAt = $data['updated_at'];
    }
}
