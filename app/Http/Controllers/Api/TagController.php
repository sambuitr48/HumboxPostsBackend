<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    /**
     * Obtener todas las etiquetas del sistema agrupadas por nombre.
     * Cada grupo contiene subcategorías con su respectivo ID.
     *
     * Ejemplo de respuesta:
     * {
     *   "Programación": [
     *     { "id": 1, "subcategory": "Backend" },
     *     { "id": 2, "subcategory": "Frontend" }
     *   ],
     *   "Diseño": [
     *     { "id": 3, "subcategory": "UI/UX" }
     *   ]
     * }
     *
     * @return JsonResponse colección de etiquetas organizadas por nombre
     */
    public function index(): JsonResponse
    {
        // Obtener todas las etiquetas y agruparlas por el campo 'name'
        $tags = Tag::all()->groupBy('name')->map(function ($group) {
            // Para cada grupo, devolver un array con id y subcategoría
            return $group->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'subcategory' => $tag->subcategory,
                ];
            })->values(); // Resetear índices
        });

        return response()->json($tags);
    }
}
