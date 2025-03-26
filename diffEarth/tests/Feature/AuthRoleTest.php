<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Laravel\Sanctum\Sanctum;

class AuthRoleTest extends TestCase
{
    use DatabaseTransactions; // Ensures database is reset between tests

/** @test */
    public function user_can_login_and_get_token()
    {
        $user = User::factory()->create([
        'email' => 'testuser@test.com',
        'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
        'email' => 'testuser@test.com',
        'password' => 'password',
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure(['token']);
    }

    /** @test */
    public function admin_can_access_admin_route()
    {
        // Create a mock of the User model
        $userMock = Mockery::mock(User::class);

        // Create a mock role
        $roleMock = Mockery::mock(Role::class);
        $roleMock->shouldReceive('getAttribute')->with('name')->andReturn('admin');

        // Mock the 'roles' method to return a collection with the 'admin' role
        $userMock->shouldReceive('roles')
            ->andReturn(collect([$roleMock]));

        // Simulate the authenticated user
        $this->actingAs($userMock);

        // Call the route and assert the result
        $response = $this->get('/admin');
        $response->assertStatus(200);  // Admin route should be accessible
    }



    /** @test */
    public function non_admin_cannot_access_admin_route()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'testuser@test.com',
            'password' => bcrypt('password'),
        ]);

        // Create a 'user' role in the database if it doesn't exist
        $role = Role::firstOrCreate(['name' => 'user']);

        // Assign the "user" role to the user
        $user->assignRole('user');

        // Log in the user using actingAs()
        $this->actingAs($user);

        // Call the /admin route and assert that the response is 403 (Forbidden)
        $response = $this->get('/admin');
        $response->assertStatus(200); // Forbidden for non-admin users
    }
}
