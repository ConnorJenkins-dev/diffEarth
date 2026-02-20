<?php

namespace Tests\Unit;

use App\Models\Collaborator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutUsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItRetrievesBiographyTextForExistingCollaborator()
    {
        $collaborator = Collaborator::factory()->create([
            'name' => 'test collab',
            'biography' => 'test biography',
        ]);

        $response = $this->getJson("api/biographyText/{$collaborator->id}");

        $response->assertStatus(200)
            ->assertJson([
                'biography' => 'test biography',
            ]);
    }

    public function testItGives404ForNonExistingCollaborator()
    {
        $response = $this->getJson("api/biographyText/999");
        $response->assertStatus(404);
    }

    public function testItUpdatesBiography()
    {
        $collaborator = Collaborator::factory()->create([
            'name' => 'test collab',
            'biography' => 'test biography',
        ]);

        $response = $this->postJson("api/biographyText/{$collaborator->id}", [
            'biography' => 'edited test biography',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('collaborators', [
            'id' => $collaborator->id,
            'biography' => 'edited test biography',
        ]);
    }
}
