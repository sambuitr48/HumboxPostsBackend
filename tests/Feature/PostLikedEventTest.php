<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\PostLiked;
use App\Models\Post;
use App\Models\User;
use App\Models\PostLike;
use App\Models\ResourceType;

class PostLikedEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_liked_event_is_broadcasted()
    {
        Event::fake([PostLiked::class]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $resourceType = ResourceType::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'resource_type_id' => $resourceType->id,
        ]);

        $like = PostLike::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        broadcast(new PostLiked($like))->toOthers();

        Event::assertDispatched(PostLiked::class, function ($event) use ($like) {
            return $event->like->id === $like->id;
        });
    }
}