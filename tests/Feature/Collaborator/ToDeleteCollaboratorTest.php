<?php

namespace Tests\Feature\Collaborator;

use App\Models\Collaborator;

class ToDeleteCollaboratorTest extends CollaboratorTestCase
{
    public function test_delete_collaborator_success(): void
    {
        $collaborator = Collaborator::factory()->create([
            'manager_id' => $this->user->id,
        ]);

        $response = $this->deleteJson("/api/collaborators/{$collaborator->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Collaborator deleted successfully']);

        $this->assertDatabaseMissing('collaborators', [
            'id' => $collaborator->id,
        ]);
    }

    public function test_delete_collaborator_with_unauthenticated_user(): void
    {
        $collaborator = Collaborator::factory()->create();

        $this->withHeader('Authorization', '');

        $response = $this->deleteJson("/api/collaborators/{$collaborator->id}");

        $response->assertStatus(401)
            ->assertJsonFragment(['error' => 'Unauthorized']);
    }

    public function test_delete_collaborator_not_found(): void
    {
        $response = $this->deleteJson("/api/collaborators/999");

        $response->assertStatus(404);
    }
}
