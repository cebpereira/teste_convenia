<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\Feature\User\UserTestCase;

class ToLogoutTest extends UserTestCase
{
    public function test_logout_successfully_invalidates_token()
    {
        $user = User::factory()->create();
        $headers = $this->authenticateUser($user);

        $response = $this->postJson('/api/auth/logout', [], $headers);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Successfully logged out']);
    }

    public function test_logout_with_user_unauthenticated()
    {
        $response = $this->withHeader('Authorization', "Bearer token")
            ->postJson('/api/auth/logout');

        $response->assertStatus(401)
            ->assertJsonFragment(['error' => 'Unauthorized']);
    }
}
