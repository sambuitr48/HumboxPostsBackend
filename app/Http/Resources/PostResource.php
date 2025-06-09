<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transforma el recurso (modelo Post) en un array que se devolverá como JSON.
     *
     * Este método se puede personalizar para controlar exactamente qué campos
     * se exponen en la API. Por ahora, se devuelve todo el contenido tal cual
     * como lo haría Eloquent mediante `toArray()`.
     *
     * @param Request $request La solicitud entrante
     * @return array<string, mixed> Datos serializados de la publicación
     */
    public function toArray(Request $request): array
    {
        // Devuelve todos los atributos del modelo por defecto.
        // Puedes personalizar esto en el futuro si necesitas filtrar o formatear datos.
        return parent::toArray($request);
    }
}
