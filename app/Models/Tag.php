<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * Este modelo representa una etiqueta (tag) que puede estar asociada a una o varias publicaciones.
     * 
     * Aunque por ahora está vacío, es común que tenga atributos como:
     * - name: nombre de la etiqueta (ej. "JavaScript")
     * - subcategory: categoría secundaria (ej. "Frontend")

     * Si se desea habilitar asignación masiva, se puede usar:
     * protected $fillable = ['name', 'subcategory'];
     * 
     * Además, se puede definir la relación inversa con Post si se requiere:
     * public function posts() {
     *     return $this->belongsToMany(Post::class, 'post_tag');
     * }
     */
}
