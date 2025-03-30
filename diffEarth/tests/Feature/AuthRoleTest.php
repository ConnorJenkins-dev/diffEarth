<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AuthRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_and_login()
    {
        // Create a role to assign to the user
        $role = Role::create(['name' => 'user']);

        // Register a new user
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Check for successful registration response
        $response->assertStatus(201)
            ->assertJsonStructure(['token', 'user', 'role']); // Adjusted to check 'role' (not 'roles')

        // Check if the user is logged in after registration
        $data = $response->json();
        $this->assertNotEmpty($data['token']);
        $this->assertEquals('Test User', $data['user']['name']);
        $this->assertEquals('testuser@test.com', $data['user']['email']);

        // Check that the role is correctly assigned as a string
        $this->assertEquals('user', $data['role']); // Adjusted for role being a string
    }


    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        // Try logging in with invalid credentials
        $response = $this->postJson('/api/login', [
            'email' => 'invaliduser@test.com',
            'password' => 'wrongpassword',
        ]);

        // Assert unauthorized response
        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized']);
    }

    /** @test */
    public function user_cannot_register_with_duplicate_email()
    {
        // Create a role and a user
        $role = Role::create(['name' => 'user']);
        $user = User::create([
            'name' => 'Existing User',
            'email' => 'existinguser@test.com',
            'password' => Hash::make('password123'),
        ]);
        $user->roles()->attach($role);

        // Attempt to register with the same email
        $response = $this->postJson('/api/register', [
            'name' => 'New User',
            'email' => 'existinguser@test.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        // Assert error message for email already in use
        $response->assertStatus(422)
            ->assertJson(['message' => 'The email has already been taken.']);
    }

    /** @test */
    public function user_can_logout()
    {
        // Register a new user
        $role = Role::create(['name' => 'user']);
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@test.com',
            'password' => Hash::make('password123'),
        ]);
        $user->roles()->attach($role);

        // Log the user in and get the token
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'testuser@test.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json()['token'];

        // Logout request with the user's token
        $response = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        // Assert successful logout
        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }

    /** @test */
    public function user_cannot_logout_without_token()
    {
        // Try to log out without a token
        $response = $this->postJson('/api/logout');

        // Assert unauthorized response
        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
