<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class PostCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('posts');
    }

    public function broadcastAs()
    {
        return 'post.created';
    }
}
