<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\ResourceTypeController;

// 👇 Ruta de posts sin middleware de autenticación
Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::post('/', [PostController::class, 'store']); // sin middleware auth
    Route::get('/{id}', [PostController::class, 'show']);
    Route::put('/{id}', [PostController::class, 'update']);
    Route::delete('/{id}', [PostController::class, 'destroy']);
    Route::post('/{id}/restore', [PostController::class, 'restore']);
    Route::delete('/{id}/force', [PostController::class, 'forceDelete']);
});

// Tags y tipos de recurso
Route::get('/tags', [TagController::class, 'index']);
Route::get('/resource-types', [ResourceTypeController::class, 'index']);

// Preflight para CORS
Route::options('/{any}', function () {
    return response()->json([], 200);
})->where('any', '.*');

// Ruta fallback
Route::fallback(function () {
    return response()->json(['message' => 'Ruta no encontrada'], 404);
});

Route::post('/test', function () {
    return response()->json(['message' => 'Funciona test']);
});
