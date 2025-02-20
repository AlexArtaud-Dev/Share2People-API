<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\CreateShareRequestDTO;
use App\Application\DTOs\UpdateShareRequestDTO;
use App\Application\UseCases\CreateShareUseCase;
use App\Application\UseCases\GetAllSharesUseCase;
use App\Application\UseCases\GetShareByIdUseCase;
use App\Application\UseCases\UpdateShareUseCase;
use App\Application\UseCases\DeleteShareUseCase;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\CreateShareRequest;
use App\Presentation\Http\Requests\UpdateShareRequest;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Shares",
 *     description="Share management endpoints"
 * )
 */
class ShareController extends Controller
{
    protected CreateShareUseCase $createShareUseCase;
    protected GetAllSharesUseCase $getAllSharesUseCase;
    protected GetShareByIdUseCase $getShareByIdUseCase;
    protected UpdateShareUseCase $updateShareUseCase;
    protected DeleteShareUseCase $deleteShareUseCase;

    public function __construct(
        CreateShareUseCase $createShareUseCase,
        GetAllSharesUseCase $getAllSharesUseCase,
        GetShareByIdUseCase $getShareByIdUseCase,
        UpdateShareUseCase $updateShareUseCase,
        DeleteShareUseCase $deleteShareUseCase
    ) {
        $this->createShareUseCase = $createShareUseCase;
        $this->getAllSharesUseCase = $getAllSharesUseCase;
        $this->getShareByIdUseCase = $getShareByIdUseCase;
        $this->updateShareUseCase = $updateShareUseCase;
        $this->deleteShareUseCase = $deleteShareUseCase;
    }

    /**
     * Get all shares.
     *
     * @OA\Get(
     *     path="/api/shares",
     *     summary="Get all shares",
     *     tags={"Shares"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of shares",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="My Share"),
     *                 @OA\Property(property="description", type="string", example="Optional description"),
     *                 @OA\Property(property="content_type", type="string", example="markdown"),
     *                 @OA\Property(property="content", type="string", example="## Markdown Content"),
     *                 @OA\Property(property="file_url", type="string", example="https://example.com/image.jpg"),
     *                 @OA\Property(property="short_code", type="string", example="abc123"),
     *                 @OA\Property(property="created_at", type="string", example="2025-02-20 12:00:00"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-02-20 12:00:00")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $shares = $this->getAllSharesUseCase->execute();
        return response()->json($shares, 200);
    }

    /**
     * Get a share by ID.
     *
     * @OA\Get(
     *     path="/api/shares/{id}",
     *     summary="Get share by ID",
     *     tags={"Shares"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Share ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Share details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="My Share"),
     *             @OA\Property(property="description", type="string", example="Optional description"),
     *             @OA\Property(property="content_type", type="string", example="markdown"),
     *             @OA\Property(property="content", type="string", example="## Markdown Content"),
     *             @OA\Property(property="file_url", type="string", example="https://example.com/image.jpg"),
     *             @OA\Property(property="short_code", type="string", example="abc123"),
     *             @OA\Property(property="created_at", type="string", example="2025-02-20 12:00:00"),
     *             @OA\Property(property="updated_at", type="string", example="2025-02-20 12:00:00")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $share = $this->getShareByIdUseCase->execute($id);
        return response()->json($share, 200);
    }

    /**
     * Create a new share.
     *
     * @OA\Post(
     *     path="/api/shares",
     *     summary="Create a new share",
     *     tags={"Shares"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "title", "content_type"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="My Share"),
     *             @OA\Property(property="description", type="string", example="Optional description"),
     *             @OA\Property(property="content_type", type="string", enum={"shortlink", "link", "code", "markdown", "image"}, example="markdown"),
     *             @OA\Property(property="content", type="string", example="## Markdown Content"),
     *             @OA\Property(property="file_url", type="string", example="https://example.com/image.jpg"),
     *             @OA\Property(property="short_code", type="string", example="abc123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Share created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="My Share"),
     *             @OA\Property(property="description", type="string", example="Optional description"),
     *             @OA\Property(property="content_type", type="string", example="markdown"),
     *             @OA\Property(property="content", type="string", example="## Markdown Content"),
     *             @OA\Property(property="file_url", type="string", example="https://example.com/image.jpg"),
     *             @OA\Property(property="short_code", type="string", example="abc123"),
     *             @OA\Property(property="created_at", type="string", example="2025-02-20 12:00:00"),
     *             @OA\Property(property="updated_at", type="string", example="2025-02-20 12:00:00")
     *         )
     *     )
     * )
     */
    public function store(CreateShareRequest $request): JsonResponse
    {
        $dto = new CreateShareRequestDTO($request->validated());
        $responseDTO = $this->createShareUseCase->execute($dto);
        return response()->json($responseDTO, 201);
    }

    /**
     * Update an existing share.
     *
     * @OA\Put(
     *     path="/api/shares/{id}",
     *     summary="Update an existing share",
     *     tags={"Shares"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Share ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content_type"},
     *             @OA\Property(property="title", type="string", example="Updated Share"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="content_type", type="string", enum={"shortlink", "link", "code", "markdown", "image"}, example="markdown"),
     *             @OA\Property(property="content", type="string", example="## Updated Content"),
     *             @OA\Property(property="file_url", type="string", example="https://example.com/updated.jpg"),
     *             @OA\Property(property="short_code", type="string", example="upd123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Share updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Updated Share"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="content_type", type="string", example="markdown"),
     *             @OA\Property(property="content", type="string", example="## Updated Content"),
     *             @OA\Property(property="file_url", type="string", example="https://example.com/updated.jpg"),
     *             @OA\Property(property="short_code", type="string", example="upd123"),
     *             @OA\Property(property="created_at", type="string", example="2025-02-20 12:00:00"),
     *             @OA\Property(property="updated_at", type="string", example="2025-02-20 12:30:00")
     *         )
     *     )
     * )
     */
    public function update(int $id, UpdateShareRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['id'] = $id;
        $dto = new UpdateShareRequestDTO($data);
        $responseDTO = $this->updateShareUseCase->execute($dto);
        return response()->json($responseDTO, 200);
    }

    /**
     * Delete a share.
     *
     * @OA\Delete(
     *     path="/api/shares/{id}",
     *     summary="Delete a share",
     *     tags={"Shares"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Share ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Share deleted successfully"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->deleteShareUseCase->execute($id);
        return response()->json(null, 204);
    }
}
