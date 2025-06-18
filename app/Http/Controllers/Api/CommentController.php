<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Events\CommentCreated;

// Simulación de usuario autenticado (en espera de implementar autenticación real)
define('DEFAULT_USER_ID', 1);

class CommentController extends Controller
{
    /**
     * Obtener todos los comentarios asociados a una publicación específica
     *
     * @param int $postId ID del post del cual se desean obtener los comentarios
     * @return JsonResponse lista de comentarios ordenados por fecha de creación
     */
    public function index($postId): JsonResponse
    {
        // Buscar los comentarios relacionados con el post
        $comments = Comment::where('post_id', $postId)
            ->orderBy('created_at', 'asc') // Orden cronológico ascendente
            ->get();

        return response()->json($comments);
    }

    /**
     * Crear y guardar un nuevo comentario en una publicación
     *
     * @param Request $request datos enviados por el frontend
     * @param int $postId ID del post al que se le agrega el comentario
     * @return JsonResponse respuesta con mensaje y comentario creado
     */
    public function store(Request $request, $postId): JsonResponse
    {
        // Validar los datos del comentario (incluye el postId como parte de la validación)
        $validator = Validator::make(
            array_merge($request->all(), ['post_id' => $postId]),
            [
                'post_id' => 'required|exists:posts,id', // Debe existir el post
                'content' => 'required|string|max:1000', // El contenido es obligatorio y limitado a 1000 caracteres
            ]
        );

        // Si la validación falla, se retorna un error con detalles
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Crear el comentario usando el usuario simulado
        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => DEFAULT_USER_ID,
            'content' => $request->content,
        ]);

        // notificaciones en tiempo real
        broadcast(new CommentCreated($comment))->toOthers();

        // Respuesta exitosa con el comentario creado
        return response()->json([
            'message' => 'Comentario creado exitosamente',
            'comment' => $comment
        ], 201);
    }
}
