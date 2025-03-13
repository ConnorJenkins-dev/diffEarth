<?php

namespace Tests\Feature;

use Tests\TestCase;

class EmailFormTest extends TestCase
{
    public function test_email_form_submission_success()
    {
        $response = $this->post('/send-email', [
            'to' => 'test@example.com',
            'subject' => 'Test Email',
            'message' => 'This is a test message.',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);
    }

    public function test_email_form_validation_errors()
    {
        $response = $this->post('/send-email', []); // No data sent

        $response->assertStatus(422); // Laravel validation fails
    }
}
