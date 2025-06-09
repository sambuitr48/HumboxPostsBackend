<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceType extends Model
{
    /**
     * Este modelo representa los tipos de recursos que puede tener una publicación.
     * Por ejemplo: imagen, video, documento, audio, etc.
     * 
     * No se define $fillable aquí porque actualmente no se crean desde formularios.
     * Si se desea permitir creación masiva, se puede agregar:
     * protected $fillable = ['name'];
     */
}
