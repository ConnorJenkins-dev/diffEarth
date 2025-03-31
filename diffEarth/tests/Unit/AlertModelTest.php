<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Alert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlertModelTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanCreateAnAlert()
    {
        $response = $this->postJson('/api/alerts', [
            'location' => 'Sample Location',
            'column' => 'Sample Column',
            'threshold' => 15,
            'emaillist' => ['test@test.com'],
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('alerts', ['location' => 'Sample Location']);
    }

    public function testItCanDeleteAnAlert()
    {
        $alert = Alert::factory()->create();
        $alert->delete();

        $this->assertDatabaseMissing('alerts', ['id' => $alert->id]);
    }
}
