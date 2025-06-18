<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\AttachedFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;
use App\Events\PostCreated;

// Simulación de usuario autenticado (temporal hasta integrar sistema de usuarios real)
define('DEFAULT_USER_ID', 1);

class PostController extends Controller
{
    /**
     * Obtener todas las publicaciones con sus relaciones cargadas
     */
    public function index(): JsonResponse
    {
        $posts = Post::with(['resourceType', 'tags', 'attachedFiles'])->get();
        return response()->json($posts);
    }

    /**
     * Crear una nueva publicación con archivos adjuntos
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        // Registrar entrada al método en el log
        Log::info("Entró al método store del PostController");

        try {
            // Validar los datos usando FormRequest
            $data = $request->validated();
            Log::info("Payload validado:", $data);

            // Decodificar las etiquetas (vienen como string JSON)
            $data['tags'] = json_decode($data['tags'], true) ?? [];

            // Instanciar el manejador de imágenes
            $imageManager = new ImageManager(Driver::class);

            // Ejecutar toda la lógica en una transacción de base de datos
            return DB::transaction(function () use ($data, $request, $imageManager) {
                // Crear la publicación
                $post = Post::create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'location' => $data['location'],
                    'resource_type_id' => $data['resource_type_id'],
                    'user_id' => DEFAULT_USER_ID,
                    'published_at' => now(),
                ]);

                // Asociar etiquetas a la publicación (si existen)
                if (!empty($data['tags'])) {
                    $post->tags()->attach($data['tags']);
                }

                // notificaciones en tiempo real
                broadcast(new PostCreated($post))->toOthers();

                // Obtener archivos adjuntos del request
                $attachedFiles = $request->all()['attached_files'] ?? [];

                // Procesar cada archivo adjunto
                foreach ($attachedFiles as $index => $fileData) {
                    $file = $request->file("attached_files.$index.file");
                    $alt = $fileData['alt_text'] ?? null;

                    if (!$file) {
                        throw new \Exception("Archivo no encontrado en el índice $index");
                    }

                    // Determinar el tipo de archivo por el MIME
                    $mime = $file->getMimeType();
                    $type = str_contains($mime, 'image') ? 'image' :
                            (str_contains($mime, 'video') ? 'video' :
                            (str_contains($mime, 'pdf') ? 'pdf' : 'unknown'));

                    // Validaciones específicas por tipo de archivo
                    switch ($type) {
                        case 'image':
                            $image = $imageManager->read($file->getRealPath());

                            // Validar dimensiones mínimas
                            if ($image->width() < 640 || $image->height() < 480) {
                                throw new \Exception('La imagen debe tener al menos 640x480 px.');
                            }

                            
                            // Validar tamaño máximo
                            if ($file->getSize() > 5 * 1024 * 1024) {
                                throw new \Exception('La imagen supera los 5MB.');
                            }
                            break;

                        case 'video':
                            $allowedExtensions = ['mp4', 'mov', 'avi'];
                            if (!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
                                throw new \Exception('Solo se permiten videos .mp4, .mov, .avi');
                            }

                            if ($file->getSize() > 20 * 1024 * 1024) {
                                throw new \Exception('El video supera los 20MB.');
                            }
                            break;

                        case 'pdf':
                            if ($file->getClientOriginalExtension() !== 'pdf') {
                                throw new \Exception('Solo se permiten archivos PDF.');
                            }

                            if ($file->getSize() > 10 * 1024 * 1024) {
                                throw new \Exception('El PDF supera los 10MB.');
                            }
                            break;

                        default:
                            throw new \Exception('Tipo de archivo no soportado.');
                    }

                    // Almacenar el archivo en el disco público
                    $path = $file->store('posts/files', 'public');

                    // Crear registro del archivo adjunto en la base de datos
                    AttachedFile::create([
                        'post_id'       => $post->id,
                        'original_name' => $file->getClientOriginalName(),
                        'file_url'      => $path,
                        'type'          => $type,
                        'alt_text'      => $alt,
                        'options'       => null,
                    ]);
                }

                // Retornar la respuesta con la publicación y sus relaciones cargadas
                return response()->json([
                    'message' => 'Post creado con éxito',
                    'post' => $post->load(['tags', 'attachedFiles'])
                ], 201);
            });

        } catch (\Throwable $e) {
            // Registrar error en el log
            Log::error('Error al crear post:', ['error' => $e->getMessage()]);

            // Retornar error al cliente
            return response()->json([
                'message' => 'Error al crear el post',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar una publicación específica por ID
     */
    public function show($id): JsonResponse
    {
        $post = Post::with(['resourceType', 'tags', 'attachedFiles'])->findOrFail($id);
        return response()->json($post);
    }

    /**
     * Actualizar publicación (aún no implementado)
     */
    public function update(StorePostRequest $request, $id): JsonResponse
    {
        return response()->json(['message' => 'Método update pendiente de implementación.']);
    }

    /**
     * Eliminación lógica (soft delete) de un post
     */
    public function destroy($id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post eliminado (soft delete)']);
    }

    /**
     * Restaurar una publicación previamente eliminada
     */
    public function restore($id): JsonResponse
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();

        return response()->json(['message' => 'Post restaurado correctamente']);
    }

    /**
     * Eliminación definitiva de una publicación
     */
    public function forceDelete($id): JsonResponse
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->forceDelete();

        return response()->json(['message' => 'Post eliminado permanentemente']);
    }
}
