<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    /**
     * Atributos que se pueden asignar masivamente.
     * Se usa para permitir create([...]) o fill([...]).
     */
    protected $fillable = [
        'post_id',  // ID de la publicación a la que pertenece el like
        'user_id'   // ID del usuario que dio like (simulado por ahora)
    ];

    /**
     * Relación: un like pertenece a una publicación.
     * Esto permite acceder a los datos de la publicación desde un like.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
