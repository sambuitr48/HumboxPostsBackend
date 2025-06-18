<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\PostCreated;
use App\Models\Post;
use App\Models\User;
use App\Models\ResourceType;

class PostCreatedEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_created_event_is_broadcasted()
    {
        Event::fake([PostCreated::class]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $resourceType = ResourceType::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'resource_type_id' => $resourceType->id,
        ]);

        broadcast(new PostCreated($post))->toOthers();

        Event::assertDispatched(PostCreated::class, function ($event) use ($post) {
            return $event->post->id === $post->id;
        });
    }
}
