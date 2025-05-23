<?php

namespace Tests\Feature\Collaborator;

class ToCreateCollaboratorTest extends CollaboratorTestCase
{
    public function test_create_collaborator_success(): void
    {
        $data = [
            'name' => 'Carlos',
            'email' => 'carlos@example.com',
            'cpf' => '12345678901',
            'city' => 'Jequié',
            'state' => 'BA',
        ];

        $response = $this->postJson('/api/collaborators', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['collaborator']);

        $this->assertDatabaseHas('collaborators', [
            'email' => 'carlos@example.com',
            'manager_id' => $this->user->id,
        ]);
    }

    public function test_create_collaborator_with_unauthenticated_user(): void
    {
        $this->withHeader('Authorization', '');

        $data = [
            'name' => 'Carlos',
            'email' => 'carlos@example.com',
            'cpf' => '12345678901',
            'city' => 'Jequié',
            'state' => 'BA',
        ];

        $response = $this->postJson('/api/collaborators', $data);

        $response->assertStatus(401)
            ->assertJsonFragment(['error' => 'Unauthorized']);
    }
}
