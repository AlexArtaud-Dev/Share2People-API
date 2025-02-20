<?php

namespace App\Presentation\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Presentation\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Models\EloquentUser;

/**
 * @OA\Tag(
 *     name="Email",
 *     description="Email verification endpoints"
 * )
 */
class EmailVerificationController extends Controller
{
    /**
     * Verify user's email.
     *
     * @OA\Get(
     *     path="/api/email/verify/{id}/{hash}",
     *     summary="Verify email address",
     *     tags={"Email"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="hash",
     *         in="path",
     *         description="Email verification hash",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Email verified successfully.")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid verification link")
     * )
     */
    public function verify(Request $request, $id, $hash): JsonResponse
    {
        $user = EloquentUser::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['error' => 'Invalid verification link'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'Email verified successfully.'], 200);
    }

    /**
     * Resend the email verification notification.
     *
     * @OA\Post(
     *     path="/api/email/resend",
     *     summary="Resend email verification link",
     *     tags={"Email"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Verification email resent",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Verification email resent.")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function resend(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);
        $user = EloquentUser::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 200);
        }

        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification email resent.'], 200);
    }
}
