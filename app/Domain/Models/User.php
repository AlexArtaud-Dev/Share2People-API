<?php

namespace App\Domain\Models;

class User
{
    private ?int $id;
    private string $name;
    private string $email;
    private string $password; // hashed password
    private \DateTime|null $emailVerifiedAt;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function __construct(string $name, string $email, string $password)
    {
        $this->id = null;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->emailVerifiedAt = null;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmailVerifiedAt(): \DateTime
    {
        return $this->emailVerifiedAt;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function hasVerifiedEmail() : bool
    {
        return ($this->getEmailVerifiedAt() instanceof \DateTime);
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setEmailVerifiedAt(\DateTime $emailVerifiedAt): void
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
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
