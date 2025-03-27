<?php

namespace Tests\Unit;

use App\Models\Post;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

// Tests broken due to clocks moving forward
class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function testItGetsAllPosts()
    {
        $User = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $Posts = Post::factory()->create(
            [
                'title' => 'test title',
                'content' => 'test content',
                'user_id' => $User->id,
                'image_base64' => 'stringofcharacters'
            ]
        );
        $response = $this->getJson('/api/post');
        $response->assertJsonFragment([
            'title' => 'test title',
            'content' => 'test content',
            'user' => $User->name,
            'image' => 'stringofcharacters',
        ]);
    }
}
