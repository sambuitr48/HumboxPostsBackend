<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SavedPost;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

// Simulación de usuario autenticado (temporal mientras se integra sistema real)
define('DEFAULT_USER_ID', 1);

class SavedPostController extends Controller
{
    /**
     * Alternar el estado de guardado de una publicación:
     * - Si ya está guardada por el usuario, se elimina.
     * - Si no está guardada, se agrega a favoritos.
     *
     * @param Request $request contiene el ID del post a guardar o quitar
     * @return JsonResponse mensaje de éxito y estado
     */
    public function toggle(Request $request): JsonResponse
    {
        // Validar que el post_id esté presente y exista
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id'
        ]);

        // Si hay errores de validación, se retorna una respuesta 422
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Buscar si el post ya está guardado por el usuario
        $saved = SavedPost::where('post_id', $request->post_id)
            ->where('user_id', DEFAULT_USER_ID)
            ->first();

        // Si ya está guardado, se elimina (se quita de favoritos)
        if ($saved) {
            $saved->delete();
            return response()->json(['message' => 'Publicación retirada de guardados']);
        } else {
            // Si no está guardado, se guarda como favorito
            SavedPost::create([
                'post_id' => $request->post_id,
                'user_id' => DEFAULT_USER_ID
            ]);
            return response()->json(['message' => 'Publicación guardada']);
        }
    }

    /**
     * Obtener todas las publicaciones guardadas por un usuario específico.
     * Se incluyen las relaciones necesarias para mostrar portada y metadatos.
     *
     * @param int $userId ID del usuario (actualmente simulado)
     * @return JsonResponse colección de publicaciones favoritas
     */
    public function index($userId): JsonResponse
    {
        // Se hace eager loading de archivos, etiquetas y tipo de recurso
        $savedPosts = SavedPost::with('post.attachedFiles', 'post.tags', 'post.resourceType')
            ->where('user_id', $userId)
            ->get()
            ->pluck('post'); // Solo se devuelven los posts relacionados

        return response()->json($savedPosts);
    }
}
