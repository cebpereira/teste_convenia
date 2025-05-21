<?php

namespace Tests\Feature\User;

use App\Models\User;

class ToGetUserTest extends UserTestCase
{
    public function test_get_authenticated_user_data()
    {
        $headers = $this->createUserAndAuthenticate();

        $response = $this->getJson('/api/users/me', $headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email'
            ]);
    }

    public function test_get_authenticated_user_unauthorized()
    {
        $response = $this->getJson('/api/users/me');

        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }
}
