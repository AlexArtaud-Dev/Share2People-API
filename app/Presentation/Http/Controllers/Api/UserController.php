<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\CreateUserRequestDTO;
use App\Application\UseCases\CreateUserUseCase;
use App\Application\UseCases\UpdateUserUseCase;
use App\Application\UseCases\DeleteUserUseCase;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\CreateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="User management endpoints"
 * )
 */
class UserController extends Controller
{
    protected CreateUserUseCase $createUserUseCase;
    protected UpdateUserUseCase $updateUserUseCase;
    protected DeleteUserUseCase $deleteUserUseCase;
    protected UserRepositoryInterface $userRepository;

    public function __construct(
        CreateUserUseCase $createUserUseCase,
        UpdateUserUseCase $updateUserUseCase,
        DeleteUserUseCase $deleteUserUseCase,
        UserRepositoryInterface $userRepository
    ) {
        $this->createUserUseCase = $createUserUseCase;
        $this->updateUserUseCase = $updateUserUseCase;
        $this->deleteUserUseCase = $deleteUserUseCase;
        $this->userRepository    = $userRepository;
    }

    /**
     * Get user details.
     *
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get user details",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="created_at", type="string", example="2025-02-20 12:00:00"),
     *             @OA\Property(property="updated_at", type="string", example="2025-02-20 12:00:00")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        return response()->json([
            'id'         => $user->getId(),
            'name'       => $user->getName(),
            'email'      => $user->getEmail(),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
        ], 200);
    }

    /**
     * Update user details.
     *
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update user details",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Name"),
     *             @OA\Property(property="email", type="string", format="email", example="updated@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="newsecret")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Updated Name"),
     *             @OA\Property(property="email", type="string", example="updated@example.com"),
     *             @OA\Property(property="created_at", type="string", example="2025-02-20 12:00:00"),
     *             @OA\Property(property="updated_at", type="string", example="2025-02-20 12:30:00")
     *         )
     *     )
     * )
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $data = $request->all();
        $responseDTO = $this->updateUserUseCase->execute($id, $data);
        return response()->json($responseDTO, 200);
    }

    /**
     * Delete a user.
     *
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="User deleted successfully"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->deleteUserUseCase->execute($id);
        return response()->json(null, 204);
    }
}
