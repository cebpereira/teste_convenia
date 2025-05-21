<?php

namespace Tests\Feature\User;

use App\Models\User;

class ToCreateUserTest extends UserTestCase
{
    public function test_create_user_with_valid_data()
    {
        $headers = $this->createUserAndAuthenticate();

        $data = [
            'name' => 'Carlos Teste',
            'email' => 'carlos@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $response = $this->postJson('/api/users', $data, $headers);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email']
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'carlos@example.com',
            'name' => 'Carlos Teste',
        ]);
    }

    public function test_create_user_with_existing_email_fails()
    {
        User::factory()->create(['email' => 'carlos@example.com']);

        $headers = $this->createUserAndAuthenticate();

        $data = [
            'name' => 'Outro Nome',
            'email' => 'carlos@example.com',
            'password' => 'outro123',
            'password_confirmation' => 'outro123',
        ];

        $response = $this->postJson('/api/users', $data, $headers);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_create_user_with_invalid_data()
    {
        $headers = $this->createUserAndAuthenticate();

        $response = $this->postJson('/api/users', [], $headers);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }
}
