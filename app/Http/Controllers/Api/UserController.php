<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"User"},
     *     summary="Create a new user",
     *     description="Create a new user",
     *     operationId="createUser",
     *     security={{ "apiAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateUserRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     * )
     */
    public function store(CreateUserRequest $request)
    {
        try {
            $response = $this->userService->create($request->validated());

            return response()->json([
                'token' => $response['token'],
                'user' => $response['user']
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()],  $e->getCode());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/me",
     *     tags={"User"},
     *     summary="Get the authenticated user",
     *     description="Get the authenticated user",
     *     operationId="getUser",
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="User retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     * )
     */
    public function show()
    {
        try {
            $user = $this->userService->get();

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()],  $e->getCode());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/me",
     *     tags={"User"},
     *     summary="Update the authenticated user",
     *     description="Update the authenticated user",
     *     operationId="updateUser",
     *     security={{ "apiAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     * )
     */
    public function update(UpdateUserRequest $request)
    {
        try {
            $response = $this->userService->update($request->validated());

            if ($response['token_invalidated']) {
                return response()->json([
                    'message' => 'User updated successfully. Token invalidated.',
                    'user' => $response['user'],
                    'token_invalidated' => $response['token_invalidated']
                ], 200);
            }

            return response()->json([
                'message' => 'User updated successfully.',
                'user' => $response['user']
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
