<?php

namespace Tests\Feature\Collaborator;

use App\Models\Collaborator;

class ToUpdateCollaboratorTest extends CollaboratorTestCase
{
    public function test_update_collaborator_success(): void
    {
        $collaborator = Collaborator::factory()->create([
            'managed_by' => $this->user->id,
            'name' => 'Old Name',
        ]);

        $data = ['name' => 'New Name'];

        $response = $this->putJson("/api/collaborators/{$collaborator->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('collaborators', [
            'id' => $collaborator->id,
            'name' => 'New Name',
        ]);
    }

    public function test_update_collaborator_with_unauthenticated_user(): void
    {
        $collaborator = Collaborator::factory()->create();

        $this->withHeader('Authorization', '');

        $response = $this->putJson("/api/collaborators/{$collaborator->id}", [
            'name' => 'Novo',
        ]);

        $response->assertStatus(401);
        $response->assertJsonFragment(['error' => 'Unauthorized']);
    }

    public function test_update_collaborator_not_found(): void
    {
        $response = $this->putJson('/api/collaborators/999', ['name' => 'Novo']);

        $response->assertStatus(404);
    }

    public function test_update_collaborator_with_no_data(): void
    {
        $collaborator = Collaborator::factory()->create(['managed_by' => $this->user->id]);

        $response = $this->putJson("/api/collaborators/{$collaborator->id}", []);

        $response->assertStatus(500)
            ->assertJsonFragment(['error' => 'No data to update']);
    }
}
