<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ToUpdateUserTest extends UserTestCase
{
    public function test_update_user_name_and_email()
    {
        $headers = $this->createUserAndAuthenticate();
        $user = User::first();

        $updateData = [
            'name' => 'Novo Nome',
            'email' => 'novo@email.com',
        ];

        $response = $this->putJson('/api/users/me', $updateData, $headers);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('users', array_merge(
            ['id' => $user->id],
            $updateData
        ));
    }

    public function test_update_user_password_invalidates_token()
    {
        $token = ['Authorization' => "Bearer ". Str::random(60).""];

        $response = $this->putJson('/api/users/me', [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ], $token);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }

    public function test_update_user_with_existing_email_fails()
    {
        User::factory()->create(['email' => 'existente@email.com']);
        $headers = $this->createUserAndAuthenticate();

        $response = $this->putJson('/api/users/me', [
            'email' => 'existente@email.com'
        ], $headers);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
