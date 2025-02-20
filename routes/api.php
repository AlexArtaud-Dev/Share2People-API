<?php

use Illuminate\Support\Facades\Route;
use App\Presentation\Http\Controllers\Api\AuthController;
use App\Presentation\Http\Controllers\Api\UserController;
use App\Presentation\Http\Controllers\Api\ShareController;
use App\Presentation\Http\Controllers\Api\PasswordController;
use App\Presentation\Http\Controllers\Api\EmailVerificationController;

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('password/forgot', [PasswordController::class, 'forgot']);
Route::post('password/reset', [PasswordController::class, 'reset'])->name('password.reset');

// Email verification endpoints are public.
Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [EmailVerificationController::class, 'resend']);

// Protected routes (requires JWT via auth:api middleware)
Route::middleware('auth:api')->group(function () {
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::get('shares', [ShareController::class, 'index']);
    Route::get('shares/{id}', [ShareController::class, 'show']);
    Route::post('shares', [ShareController::class, 'store']);
    Route::put('shares/{id}', [ShareController::class, 'update']);
    Route::delete('shares/{id}', [ShareController::class, 'destroy']);
});
