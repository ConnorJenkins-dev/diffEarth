<?php

namespace Tests\Feature;

use App\Models\Deployment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeploymentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a new deployment.
     */
    public function test_create_new_deployment()
    {
        // Send POST request to create a deployment
        $response = $this->postJson('/api/deployments', [
            'uid' => 'cf240003',
            'name' => 'New Deployment',
            'latitude' => 72.0,
            'longitude' => -40.0,
            'description' => 'Deployed on July 22, 2024',
        ]);

        // Assert successful creation
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Deployment added successfully.',
                'data' => [
                    'uid' => 'cf240003',
                    'name' => 'New Deployment',
                    'latitude' => 72.0,
                    'longitude' => -40.0,
                    'description' => 'Deployed on July 22, 2024',
                ],
            ]);

        // Verify the deployment exists in the database
        $this->assertDatabaseHas('deployments', [
            'uid' => 'cf240003',
            'name' => 'New Deployment',
        ]);
    }

    /**
     * Test validation errors when creating a deployment.
     */
    public function test_validation_errors_on_creation()
    {
        // Send invalid POST request
        $response = $this->postJson('/api/deployments', [
            'uid' => '', // Missing UID
            'name' => 'New Deployment',
            'latitude' => 100, // Invalid latitude
            'longitude' => -200, // Invalid longitude
        ]);

        // Assert validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['uid', 'latitude', 'longitude']);
    }

    /**
     * Test fetching a specific deployment by ID.
     */
    public function test_fetch_specific_deployment()
    {
        // Create a test deployment
        $deployment = Deployment::factory()->create([
            'uid' => 'cf240004',
            'name' => 'Test Deployment',
            'latitude' => 60.0,
            'longitude' => -150.0,
        ]);

        // Fetch deployment by ID
        $response = $this->getJson("/api/deployments/{$deployment->id}");

        // Assert successful fetch
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'uid',
                'name',
                'latitude',
                'longitude',
                'description',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'uid' => 'cf240004',
                'name' => 'Test Deployment',
                'latitude' => 60.0,
                'longitude' => -150.0,
            ]);
    }

    /**
     * Test handling non-existent deployment.
     */
    public function test_handle_non_existent_deployment()
    {
        // Fetch non-existent deployment
        $response = $this->getJson('/api/deployments/9999');

        // Assert 404 error with custom message
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Deployment] 9999',
            ]);
    }
}
