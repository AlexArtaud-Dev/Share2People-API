<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\CreateUserRequestDTO;
use App\Application\DTOs\LoginUserRequestDTO;
use App\Application\DTOs\LoginUserResponseDTO;
use App\Application\UseCases\RegisterUserUseCase;
use App\Application\UseCases\LoginUserUseCase;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\CreateUserRequest;
use App\Presentation\Http\Requests\LoginUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="JWT Authentication endpoints"
 * )
 */
class AuthController extends Controller
{
    protected RegisterUserUseCase $registerUserUseCase;
    protected LoginUserUseCase $loginUserUseCase;

    /**
     * Create a new AuthController instance.
     * Routes "login" and "register" are public.
     */
    public function __construct(
        RegisterUserUseCase $registerUserUseCase,
        LoginUserUseCase $loginUserUseCase
    ) {
        // Only protect routes other than login and register.
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->registerUserUseCase = $registerUserUseCase;
        $this->loginUserUseCase    = $loginUserUseCase;
    }

    /**
     * Register a new user.
     *
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully. A verification email has been sent.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registered successfully. Please check your email to verify your account."),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com")
     *             )
     *         )
     *     )
     * )
     */
    public function register(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $dto  = new CreateUserRequestDTO($data);
        $responseDTO = $this->registerUserUseCase->execute($dto);

        return response()->json([
            'message' => 'User registered successfully. Please check your email to verify your account.',
            'user'    => $responseDTO,
        ], 201);
    }

    /**
     * Login user and return a JWT.
     *
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="YOUR_JWT_TOKEN"),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Email not verified"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $dto = new LoginUserRequestDTO($request->validated());

        try {
            $loginResponse = $this->loginUserUseCase->execute($dto);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], $ex->getCode() ?: 400);
        }

        return response()->json([
            'access_token' => $loginResponse->token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user_id'      => $loginResponse->user_id,
            'name'         => $loginResponse->name,
            'email'        => $loginResponse->email,
        ]);
    }

    /**
     * Get the authenticated user's details.
     *
     * @OA\Get(
     *     path="/api/me",
     *     summary="Get authenticated user details",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com")
     *         )
     *     )
     * )
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (invalidate the token).
     *
     * @OA\Post(
     *     path="/api/logout",
     *     summary="User logout",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @OA\Post(
     *     path="/api/refresh",
     *     summary="Refresh JWT token",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="NEW_JWT_TOKEN"),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     )
     * )
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Return a standardized response with the JWT token details.
     *
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ]);
    }
}
