<?php

namespace App\Events;

use App\Models\PostLike;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;

class PostLiked implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $like;

    public function __construct(PostLike $like)
    {
        $this->like = $like;
    }

    public function broadcastOn()
    {
        return new Channel('likes');
    }

    public function broadcastAs()
    {
        return 'post.liked';
    }
}
