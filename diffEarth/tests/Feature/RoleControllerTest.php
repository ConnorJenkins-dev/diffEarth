<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_get_all_roles_returns_roles_successfully()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'editor']);

        $response = $this->getJson('api/roles');

        $response->assertStatus(200)
            ->assertJson(['admin', 'editor']);
    }

    /** @test */
    public function test_add_role_successfully_creates_role()
    {
        $response = $this->postJson('api/roles/add-role', [
            'name' => 'moderator',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Role created successfully',
                'role' => ['name' => 'moderator'],
            ]);

        $this->assertDatabaseHas('roles', ['name' => 'moderator']);
    }

    /** @test */
    public function test_add_role_fails_when_name_is_missing()
    {
        $response = $this->postJson('api/roles/add-role', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }
}
