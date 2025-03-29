<?php

namespace Tests\Feature;

use App\Models\Dataset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class DatasetviewTest extends TestCase
{
    use RefreshDatabase;

    // Test that the API returns a successful response with datasets
    public function test_api_returns_datasets_successfully(): void
    {
        // Arrange: Create some datasets
        Dataset::factory()->count(5)->create();

        // Act: Send a GET request to the /api/datasets endpoint
        $response = $this->getJson('/api/datasets');

        // Assert: Verify successful status and structure
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'dataset_name',
                    'updated_at',
                    'created_at',
                    'metadata',
                ],
            ],
            'current_page',
            'last_page',
            'next_page_url',
            'prev_page_url',
        ]);
    }

    // Test that the API returns paginated datasets
    public function test_api_returns_paginated_results(): void
    {
        // Arrange: Create 55 datasets (more than one page)
        Dataset::factory()->count(55)->create();

        // Act: Send a GET request for the first page
        $response = $this->getJson('/api/datasets?page=1');

        // Assert: Verify first page contains 50 results
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(50, 'data');

        // Act: Send a GET request for the second page
        $response = $this->getJson('/api/datasets?page=2');

        // Assert: Verify the second page contains 5 results
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');
    }

    // Test that the API returns datasets ordered by ID descending
    public function test_api_returns_datasets_ordered_by_id_desc(): void
    {
        // Arrange: Create datasets in a specific order
        Dataset::factory()->create(['dataset_name' => 'Dataset 1', 'id' => 1]);
        Dataset::factory()->create(['dataset_name' => 'Dataset 2', 'id' => 2]);
        Dataset::factory()->create(['dataset_name' => 'Dataset 3', 'id' => 3]);

        // Act: Send the GET request
        $response = $this->getJson('/api/datasets');

        // Assert: Verify the order of datasets
        $responseData = $response->json('data');

        // Check descending order
        $this->assertEquals(3, $responseData[0]['id']);
        $this->assertEquals(2, $responseData[1]['id']);
        $this->assertEquals(1, $responseData[2]['id']);
    }

    // Test handling when there are no datasets
    public function test_api_handles_no_datasets(): void
    {
        // Act: Send a GET request when no datasets exist
        $response = $this->getJson('/api/datasets');

        // Assert: Verify successful response with an empty data array
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [],
            'current_page' => 1,
            'last_page' => 1,
            'next_page_url' => null,
            'prev_page_url' => null,
        ]);
    }
}
