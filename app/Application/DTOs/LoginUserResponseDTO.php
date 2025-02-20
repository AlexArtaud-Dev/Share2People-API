<?php

namespace App\Application\DTOs;

class LoginUserResponseDTO
{
    public string $token;
    public int $user_id;
    public string $name;
    public string $email;

    public function __construct(array $data)
    {
        $this->token   = $data['token'] ?? '';
        $this->user_id = $data['user_id'] ?? 0;
        $this->name    = $data['name'] ?? '';
        $this->email   = $data['email'] ?? '';
    }
}
