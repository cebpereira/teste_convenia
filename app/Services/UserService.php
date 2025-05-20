<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    /**
     * Create a new user with the given data and return a JWT token.
     *
     * @param  object  $data
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function create($data)
    {
        try {
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

            return response()->json([
                'token' => $token,
                'user' => $user,
            ], 201);
        } catch (Exception $ex) {
            return response()->json(['error' => 'Failed to create user: ' . $ex->getMessage()], 500);
        } catch (JWTException $exjwt) {
            return response()->json(['error' => 'Could not create token: ' . $exjwt->getMessage()], 500);
        }
    }

    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function get()
    {
        try {
            $user = Auth::user();

            throw_if(!$user, 'User not found', 404);

            return response()->json($user, 200);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        } catch (JWTException $exjwt) {
            return response()->json(['error' => $exjwt->getMessage()], 500);
        }
    }

    /**
     * Update the authenticated user with the given data.
     *
     * @param  object  $data  The data required to update the user.
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception If the user is not found or user update fails.
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function update($data)
    {
        try {
            $user = User::find(Auth::user()->id);

            throw_if(!$user, 'User not found', 404);

            $updateData = $this->prepareUpdateData($data);
            $emailChanged = isset($updateData['email']) && $updateData['email'] !== $user->email;
            $passwordChanged = isset($updateData['password']);

            $user->update($updateData);

            $response = ['user' => $user];

            if ($emailChanged || $passwordChanged) {
                JWTAuth::invalidate(JWTAuth::fromUser($user));
                $response['message'] = 'Credentials changed, please login again';
            }

            return response()->json($response, 200);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        } catch (JWTException $exjwt) {
            return response()->json(['error' => $exjwt->getMessage()], 500);
        }
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

        return array_filter($updateData, function($value) {
            return $value !== null;
        });
    }
}
