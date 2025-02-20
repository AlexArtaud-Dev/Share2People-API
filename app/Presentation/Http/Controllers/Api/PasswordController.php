<?php

namespace App\Presentation\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\JsonResponse;
use App\Presentation\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Password",
 *     description="Password reset and recovery endpoints"
 * )
 */
class PasswordController extends Controller
{
    /**
     * Send a password reset link to the given email.
     *
     * @OA\Post(
     *     path="/api/password/forgot",
     *     summary="Send password reset link",
     *     tags={"Password"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reset link sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="We have emailed your password reset link!")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function forgot(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['error' => __($status)], 422);
    }

    /**
     * Reset the given user's password.
     *
     * @OA\Post(
     *     path="/api/password/reset",
     *     summary="Reset password",
     *     tags={"Password"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "token", "password", "password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="token", type="string", example="reset_token_here"),
     *             @OA\Property(property="password", type="string", format="password", example="newsecret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newsecret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset successfully")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['error' => __($status)], 422);
    }
}
