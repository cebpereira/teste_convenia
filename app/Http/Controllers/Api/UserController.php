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
        return $this->userService->create($request->all());
    }

    /**
     * @OA\Get(
     *     path="/api/users/me",
     *     tags={"User"},
     *     summary="Get the authenticated user",
     *     description="Get the authenticated user",
     *     operationId="getUser",
     *     @OA\Response(
     *         response=200,
     *         description="User retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     * )
     */
    public function show()
    {
        return $this->userService->get();
    }

    /**
     * @OA\Put(
     *     path="/api/users/me",
     *     tags={"User"},
     *     summary="Update the authenticated user",
     *     description="Update the authenticated user",
     *     operationId="updateUser",
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
        return $this->userService->update($request->all());
    }
}
