<?php

namespace Tests\Feature;

use Tests\TestCase;

class EmailFormTest extends TestCase
{
    public function test_email_form_submission_success()
    {
        $response = $this->post('/api/send-email', [
            'location' => 'TestLocation',
            'column' => 'TestColumn',
            'threshold' => 100,
            'emails' => ['test@example.com'],
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Emails sent and alert saved successfully!']);
    }

    public function test_email_form_validation_errors()
    {
        $response = $this->postJson('/api/send-email', []); // Use postJson for API tests

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['location', 'column', 'threshold', 'emails']);
    }
}
