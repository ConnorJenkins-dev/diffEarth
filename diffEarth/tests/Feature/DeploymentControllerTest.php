<?php

namespace Tests\Feature;

use App\Models\Deployment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeploymentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a new deployment.
     */
    public function test_create_new_deployment()
    {
        $uid = (string) Str::uuid();
        // Send POST request to create a deployment
        $response = $this->postJson('api/deployments', [
            'uid' => $uid,
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
                    'uid' => $uid,
                    'name' => 'New Deployment',
                    'latitude' => 72.0,
                    'longitude' => -40.0,
                    'description' => 'Deployed on July 22, 2024',
                ],
            ]);

        // Verify the deployment exists in the database
        $this->assertDatabaseHas('deployments', [
            'uid' => $uid,
            'name' => 'New Deployment',
        ]);
    }

    /**
     * Test validation errors when creating a deployment.
     */
    public function test_validation_errors_on_creation()
    {
        // Send invalid POST request
        $response = $this->postJson('api/deployments', [
            'uid' => '', // Missing UID
            'name' => 'New Deployment',
            'latitude' => 100, // Invalid latitude
            'longitude' => -200, // Invalid longitude
        ]);

        // Assert validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['latitude', 'longitude']);
    }

    /**
     * Test fetching a specific deployment by ID.
     */
    public function test_fetch_specific_deployment()
    {
        $uid = (string) Str::uuid();
        // Create a test deployment
        $deployment = Deployment::factory()->create([
            'uid' => $uid,
            'name' => 'Test Deployment',
            'latitude' => 60.0,
            'longitude' => -150.0,
        ]);

        // Fetch deployment by ID
        $response = $this->getJson("api/deployments/{$deployment->id}");

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
                'uid' => $uid,
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
        $response = $this->getJson('api/deployments/9999');

        // Assert 404 error with custom message
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Deployment] 9999',
            ]);
    }

    /**
     * Test fetching all deployments.
     */
    public function test_index_returns_all_deployments()
    {
        Deployment::factory()->count(3)->create();

        $response = $this->getJson('api/deployments');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /**
     * Test deleting a deployment.
     */
    public function test_destroy_deletes_a_deployment()
    {
        $deployment = Deployment::factory()->create();

        $response = $this->deleteJson("api/deployments/{$deployment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Deployment deleted.'
            ]);

        $this->assertDatabaseMissing('deployments', [
            'id' => $deployment->id
        ]);
    }

    /**
     * Test fetching the in-progress deployment (success).
     */
    public function test_in_progress_index_returns_data_if_exists()
    {
        \App\Models\DeploymentInProgress::create([
            'id' => 1,
            'name' => 'Test In Progress',
            'info' => 'Test info',
            'layout' => json_encode([
                ['x' => 0, 'y' => 0, 'w' => 2, 'h' => 2, 'i' => 'graph_0', 'itemData' => []]
            ])
        ]);

        // Double check it’s in the database
        $this->assertDatabaseHas('deployments_in_progress', [
            'id' => 1,
            'name' => 'Test In Progress'
        ]);

        // Now try to retrieve
        $response = $this->getJson('api/deployments/in-progress');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Test In Progress']);
    }

    /**
     * Test fetching the in-progress deployment (404).
     */
    public function test_in_progress_index_returns_404_if_not_exists()
    {
        $response = $this->getJson('api/deployments/in-progress');

        $response->assertStatus(404);
    }

    /**
     * Test storing/updating in-progress deployment.
     */
    public function test_in_progress_store_creates_or_updates_deployment()
    {
        $layout = [['x' => 0, 'y' => 0, 'w' => 2, 'h' => 2, 'i' => 'graph_0', 'itemData' => []]];

        $response = $this->postJson('api/deployments/in-progress', [
            'id' => 1,
            'name' => 'Untitled Dashboard',
            'layout' => $layout,
            'info' => 'Updated info'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Layout saved successfully!'
            ]);

        $this->assertDatabaseHas('deployments_in_progress', [
            'id' => 1,
            'info' => 'Updated info'
        ]);
    }

    /**
     * Test fetching deployment by UUID.
     */
    public function test_show_by_uuid_returns_correct_data()
    {
        $deployment = Deployment::factory()->create([
            'uid' => 'abc123',
            'name' => 'UUID Test',
            'layout' => [['i' => 'graph_0']],
            'info' => 'Extra info',
            'description' => 'Desc'
        ]);

        $response = $this->getJson('api/dashboard/abc123');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'UUID Test',
                'info' => 'Extra info',
                'description' => 'Desc',
            ]);
    }
}
