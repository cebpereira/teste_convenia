<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;


class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function store(CreateUserRequest $request)
    {
        return $this->userService->create($request->all());
    }


    public function show()
    {
        return $this->userService->get();
    }


    public function update(UpdateUserRequest $request)
    {
        return $this->userService->update($request->all());
    }
}
