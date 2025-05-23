<?php

namespace Tests\Feature\Collaborator;

use App\Models\Collaborator;

class ToListCollaboratorTest extends CollaboratorTestCase
{
    public function test_list_collaborators_success(): void
    {
        Collaborator::factory()->count(2)->create([
            'manager_id' => $this->user->id,
        ]);

        $response = $this->getJson('/api/collaborators');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    public function test_list_returns_empty_array_if_no_collaborators_found(): void
    {
        $response = $this->getJson('/api/collaborators');

        $response->assertStatus(200);
        $response->assertExactJson([]);
    }

    public function test_list_collaborators_with_unauthenticated_user(): void
    {
        $this->withHeader('Authorization', '');

        $response = $this->getJson('/api/collaborators');

        $response->assertStatus(401);
        $response->assertJsonFragment(['error' => 'Unauthorized']);
    }
}
