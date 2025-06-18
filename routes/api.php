<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\ResourceTypeController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostLikeController;
use App\Http\Controllers\Api\SavedPostController;

// Agrupación de rutas relacionadas con publicaciones
Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']); // Obtener todas las publicaciones
    Route::post('/', [PostController::class, 'store']); // Crear nueva publicación
    Route::get('/{id}', [PostController::class, 'show']); // Mostrar una publicación específica
    Route::put('/{id}', [PostController::class, 'update']); // Actualizar publicación (pendiente)
    Route::delete('/{id}', [PostController::class, 'destroy']); // Eliminado lógico (soft delete)
    Route::post('/{id}/restore', [PostController::class, 'restore']); // Restaurar publicación eliminada
    Route::delete('/{id}/force', [PostController::class, 'forceDelete']); // Eliminación permanente

    Route::get('/{postId}/comments', [CommentController::class, 'index']); // Obtener comentarios de un post
    Route::get('/{postId}/likes/count', [PostLikeController::class, 'count']); // Contador de likes
});

// Crear nuevo comentario para un post
Route::post('/posts/{postId}/comments', [CommentController::class, 'store']);

// Alternar like (si existe, lo elimina; si no, lo crea)
Route::post('/likes/toggle', [PostLikeController::class, 'toggle']);

// Alternar publicación guardada
Route::post('/saved/toggle', [SavedPostController::class, 'toggle']);

// Obtener publicaciones guardadas por ID de usuario
Route::get('/saved/{userId}', [SavedPostController::class, 'index']);

// Obtener todas las etiquetas disponibles
Route::get('/tags', [TagController::class, 'index']);

// Obtener todos los tipos de recurso disponibles
Route::get('/resource-types', [ResourceTypeController::class, 'index']);