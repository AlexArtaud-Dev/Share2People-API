<?php


namespace App\Providers;

use App\Application\Services\AuthServiceInterface;
use App\Application\Services\EmailVerificationServiceInterface;
use App\Domain\Repositories\ShareRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\EloquentShareRepository;
use App\Infrastructure\Persistence\EloquentUserRepository;
use App\Infrastructure\Services\EloquentAuthService;
use App\Infrastructure\Services\EmailVerificationService;
use App\Infrastructure\Services\JWTAuthService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ShareRepositoryInterface::class,
            EloquentShareRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );

        $this->app->bind(
            AuthServiceInterface::class,
            EloquentAuthService::class
        );

        $this->app->bind(
            AuthServiceInterface::class,
            JWTAuthService::class
        );

        $this->app->bind(
            EmailVerificationServiceInterface::class,
            EmailVerificationService::class
        );

    }
}
