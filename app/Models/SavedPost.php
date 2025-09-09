<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedPost extends Model
{
    /**
     * Atributos que se pueden asignar masivamente.
     * Se usa para crear registros mediante create([...]) o fill([...]).
     */
    protected $fillable = [
        'post_id',  // ID de la publicación que se guarda
        'user_id'   // ID del usuario que guarda la publicación (simulado por ahora)
    ];

    /**
     * Relación: una publicación guardada pertenece a una publicación.
     * Permite acceder desde el guardado a la información completa del post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
