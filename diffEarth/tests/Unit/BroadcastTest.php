<?php

namespace Tests\Unit;

use App\Events\NewPostCreated;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BroadcastTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_post_created_event_is_dispatched()
    {
        Event::fake();

        $user = User::factory()->create();

        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Sample content for broadcasting',
            'user_id' => $user->id,
        ]);

        event(new NewPostCreated($post));

        Event::assertDispatched(NewPostCreated::class);
    }

    public function test_new_post_created_event_payload()
    {
        Event::fake();

        $user = User::factory()->create();

        $post = Post::create([
            'title' => 'Payload Post',
            'content' => 'This is a payload test.',
            'user_id' => $user->id,
        ]);

        event(new NewPostCreated($post));

        Event::assertDispatched(NewPostCreated::class, function ($event) use ($post) {
            $payload = $event->broadcastWith();

            return $payload['post'] === $post->id;
        });
    }
}
