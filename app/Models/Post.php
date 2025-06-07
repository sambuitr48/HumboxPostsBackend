<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use SoftDeletes; 

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'resource_type_id',
        'allow_download',
        'allow_comments',
        'published_at'
    ];

    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class);
    }

    public function attachedFiles(): HasMany
    {
        return $this->hasMany(AttachedFile::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    /*public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_collaborators');
    }*/

    // Relación falsa para evitar errores
    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_collaborators'); // fake para evitar SQL
    }
}
