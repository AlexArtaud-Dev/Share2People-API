<?php

namespace App\Application\Services;

interface EmailVerificationServiceInterface
{
    public function sendVerificationEmail(int $userId): void;
}
