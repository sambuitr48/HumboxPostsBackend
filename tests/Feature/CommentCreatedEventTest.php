<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\CommentCreated;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\ResourceType;

class CommentCreatedEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_created_event_is_broadcasted()
    {
        Event::fake([CommentCreated::class]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $resourceType = ResourceType::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'resource_type_id' => $resourceType->id,
        ]);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        broadcast(new CommentCreated($comment))->toOthers();

        Event::assertDispatched(CommentCreated::class, function ($event) use ($comment) {
            return $event->comment->id === $comment->id;
        });
    }
}