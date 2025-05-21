<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class UserTestCase extends TestCase
{
    use RefreshDatabase;

    protected function authenticateUser(User $user): array
    {
        $token = JWTAuth::fromUser($user);
        return ['Authorization' => "Bearer $token"];
    }

    protected function createUserAndAuthenticate(): array
    {
        $user = User::factory()->create();
        return $this->authenticateUser($user);
    }
}
