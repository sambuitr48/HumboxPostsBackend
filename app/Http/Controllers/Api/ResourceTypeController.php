<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ResourceType;
use Illuminate\Http\JsonResponse;

class ResourceTypeController extends Controller
{
    /**
     * Obtener todos los tipos de recurso disponibles en el sistema.
     * Solo se devuelven los campos 'id' y 'name' para cada tipo.
     *
     * @return JsonResponse lista de tipos de recurso (por ejemplo: imagen, video, documento, etc.)
     */
    public function index(): JsonResponse
    {
        return response()->json(ResourceType::all(['id', 'name']));
    }
}
