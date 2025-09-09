<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

// Simulación de usuario autenticado (en espera de integrar sistema real de usuarios)
define('DEFAULT_USER_ID', 1);

class PostLikeController extends Controller
{
    /**
     * Alternar el "like" de una publicación.
     * Si ya existe el like del usuario, lo elimina.
     * Si no existe, lo crea.
     *
     * @param Request $request datos enviados desde el frontend (post_id)
     * @return JsonResponse respuesta indicando si se agregó o eliminó el like
     */
    public function toggle(Request $request): JsonResponse
    {
        // Validar que el post_id esté presente y exista en la base de datos
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Buscar si el usuario ya le dio like al post
        $like = PostLike::where('post_id', $request->post_id)
                        ->where('user_id', DEFAULT_USER_ID)
                        ->first();

        // Si ya le dio like, se elimina
        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Like eliminado']);
        } else {
            // Si no le había dado like, se crea
            PostLike::create([
                'post_id' => $request->post_id,
                'user_id' => DEFAULT_USER_ID
            ]);
            return response()->json(['message' => 'Like agregado']);
        }
    }

    /**
     * Obtener la cantidad total de likes de una publicación específica
     *
     * @param int $postId ID de la publicación
     * @return JsonResponse respuesta con el número de likes
     */
    public function count($postId): JsonResponse
    {
        $count = PostLike::where('post_id', $postId)->count();

        return response()->json(['likes' => $count]);
    }
}
