<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * Atributos que pueden ser asignados masivamente.
     * Permite crear o actualizar comentarios usando create([...]) o fill([...]).
     */
    protected $fillable = [
        'post_id',   // ID del post al que pertenece el comentario
        'user_id',   // ID del usuario que escribió el comentario
        'content'    // Contenido textual del comentario
    ];

    /**
     * Relación: un comentario pertenece a una publicación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Relación: un comentario pertenece a un usuario.
     * Aunque no se use todavía (porque no hay auth real), se deja preparada.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
