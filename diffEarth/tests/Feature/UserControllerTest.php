<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_retrieve_their_roles()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'admin']);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->getJson('/api/user/roles');

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'roles' => [$role->name],
            ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_retrieve_roles()
    {
        $response = $this->getJson('/api/user/roles');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
