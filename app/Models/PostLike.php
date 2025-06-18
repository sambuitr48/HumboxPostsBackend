<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostLike extends Model
{
        use HasFactory;
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
