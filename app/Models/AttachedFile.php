<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttachedFile extends Model
{
    /**
     * Atributos que se pueden asignar masivamente (mass assignment).
     * Permite crear registros usando create([...]) o fill([...]).
     */
    protected $fillable = [
        'post_id',         // Relación con el post al que pertenece el archivo
        'original_name',   // Nombre original del archivo subido
        'file_url',        // Ruta relativa donde se almacena el archivo
        'type',            // Tipo de archivo: image, video o pdf
        'alt_text',        // Texto alternativo para accesibilidad o contexto
        'options'          // Campo flexible para futuras configuraciones (JSON)
    ];

    /**
     * Conversión automática del campo 'options' desde/para JSON.
     * Laravel lo manejará como array en la aplicación.
     */
    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Relación: un archivo adjunto pertenece a una publicación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
