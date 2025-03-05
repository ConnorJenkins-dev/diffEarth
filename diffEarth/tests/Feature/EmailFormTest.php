<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailFormTest extends TestCase
{
    use RefreshDatabase;

    public function testEmailFormSubmissionSuccess()
    {
        $response = $this->post('/api/send-email', [
            'location' => 'place',
            'column' => 'time',
            'threshold' => 10,
            'emails' => ['test@example.com'],
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Emails sent and alert saved successfully!']);
    }

    public function testEmailFormValidationErrors()
    {
        $response = $this->postJson('/api/send-email', []); // Use postJson for API tests

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['location', 'column', 'threshold', 'emails']);
    }
}
