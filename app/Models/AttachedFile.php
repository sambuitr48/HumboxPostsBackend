<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttachedFile extends Model
{
    protected $fillable = [
        'post_id',
        'original_name',
        'file_url',
        'type',
        'alt_text',
        'options'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}