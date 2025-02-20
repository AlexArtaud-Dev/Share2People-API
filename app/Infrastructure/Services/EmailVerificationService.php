<?php

namespace App\Infrastructure\Services;

use App\Application\Services\EmailVerificationServiceInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Models\EloquentUser;

class EmailVerificationService implements EmailVerificationServiceInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function sendVerificationEmail(int $userId): void
    {
        // Retrieve the Eloquent user to send the notification.
        $user = EloquentUser::find($userId);
        if ($user) {
            $user->sendEmailVerificationNotification();
        }
    }
}
