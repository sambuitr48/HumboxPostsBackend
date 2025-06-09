<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use SoftDeletes; // Habilita el borrado lógico (soft delete)

    /**
     * Atributos que pueden ser asignados masivamente.
     */
    protected $fillable = [
        'user_id',          // ID del autor de la publicación
        'title',            // Título de la publicación
        'description',      // Contenido principal
        'location',         // Ubicación (opcional)
        'resource_type_id', // Tipo de recurso asociado (imagen, video, PDF, etc.)
        'allow_download',   // Permite o no descargar archivos (bool)
        'allow_comments',   // Permite o no comentar (bool)
        'published_at'      // Fecha de publicación
    ];

    /**
     * Relación: la publicación pertenece a un tipo de recurso.
     */
    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class);
    }

    /**
     * Relación: una publicación puede tener muchos archivos adjuntos.
     */
    public function attachedFiles(): HasMany
    {
        return $this->hasMany(AttachedFile::class);
    }

    /**
     * Relación: una publicación puede tener muchas etiquetas (tags).
     * Usa la tabla pivote 'post_tag'.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    /**
     * Relación: colaboradores de la publicación.
     * Temporalmente se usa una relación falsa para evitar errores con usuarios reales.
     * En el futuro, esto se debe conectar con el modelo `User`.
     */
    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_collaborators'); // Relación falsa
    }

    /**
     * Relación: una publicación puede tener muchos comentarios.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relación: una publicación puede recibir muchos likes.
     */
    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    /**
     * Relación: usuarios que han guardado esta publicación.
     */
    public function savedBy()
    {
        return $this->hasMany(SavedPost::class);
    }
}
