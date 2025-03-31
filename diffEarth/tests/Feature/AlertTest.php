<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Alert;

class AlertTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanCreateAnAlert()
    {
        $response = $this->postJson('/api/alerts', [
            'location' => 'Test Location',
            'column' => 'Test Column',
            'threshold' => 5,
            'emaillist' => ['test@test.com'], // Ensure it's sent as an array
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Alert created successfully']);

        $this->assertDatabaseHas('alerts', ['location' => 'Test Location']);
    }


    public function testUserCanDeleteAnAlert()
    {
        $alert = Alert::factory()->create();

        $response = $this->deleteJson("/api/alerts/{$alert->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Alert deleted successfully']);

        $this->assertDatabaseMissing('alerts', ['id' => $alert->id]);
    }
}
