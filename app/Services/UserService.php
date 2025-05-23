<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    /**
     * Create a new user instance.
     *
     * @param  array  $data
     * @return array
     *
     * @throws \Exception
     */
    public function create($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        throw_if(!$user, 'Failed to create user', 500);

        $token = JWTAuth::fromUser($user);

        throw_if(!$token, 'Failed to create token', 500);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Get the authenticated user.
     *
     * @return \App\Models\User
     *
     * @throws \Exception
     */
    public function get()
    {
        $user = Auth::user();

        throw_if(!$user, 'User not found', 404);

        return $user;
    }

    /**
     * Update the authenticated user.
     *
     * @param  array  $data  The data to be used to update the user.
     * @return array
     *
     * @throws \Exception
     */
    public function update($data)
    {
        $user = User::find(Auth::user()->id);

        throw_if(!$user, 'User not found', 404);

        $updateData = $this->prepareUpdateData($data);
        $emailChanged = isset($updateData['email']) && $updateData['email'] !== $user->email;
        $passwordChanged = isset($updateData['password']) && !Hash::check($updateData['password'], $user->password);

        $user->update($updateData);

        $tokenInvalidated = false;

        if ($emailChanged || $passwordChanged) {
            JWTAuth::invalidate(JWTAuth::fromUser($user));
            $tokenInvalidated = true;
        }

        return [
            'user' => $user,
            'token_invalidated' => $tokenInvalidated
        ];
    }

    /**
     * Prepare the data to be used when updating a user.
     *
     * We only allow updating the user's name and email. If the password is provided,
     * we hash it.
     *
     * @param  object|array  $data
     * @return array
     */
    protected function prepareUpdateData($data)
    {
        $updateData = Arr::only((array)$data, ['name', 'email', 'password']);

        if (isset($updateData['password'])) {
            $updateData['password'] = Hash::make($updateData['password']);
        }

        return array_filter($updateData, function ($value) {
            return $value !== null;
        });
    }
}
