<?php

namespace Tests\Feature\Collaborator;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class CollaboratorTestCase extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = auth()->login($this->user);

        $this->withHeader('Authorization', "Bearer {$this->token}");
    }
}
