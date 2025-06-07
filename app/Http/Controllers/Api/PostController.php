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

define('DEFAULT_USER_ID', 1);

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::with(['resourceType', 'tags', 'attachedFiles'])->get();
        return response()->json($posts);
    }

public function store(StorePostRequest $request): JsonResponse
{
    Log::info("💥 Entró al método store del PostController");

    try {
        $data = $request->validated();
        Log::info("📦 Payload validado:", $data);

        // Decodificar tags que vienen como JSON
        $data['tags'] = json_decode($data['tags'], true) ?? [];

        $imageManager = new ImageManager(Driver::class);

        return DB::transaction(function () use ($data, $request, $imageManager) {
            $post = Post::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'location' => $data['location'],
                'resource_type_id' => $data['resource_type_id'],
                'user_id' => DEFAULT_USER_ID,
                'published_at' => now(),
            ]);

            // Asociar etiquetas
            if (!empty($data['tags'])) {
                $post->tags()->attach($data['tags']);
            }

            // Extraer archivos del request
            $attachedFiles = $request->all()['attached_files'] ?? [];

            foreach ($attachedFiles as $index => $fileData) {
                $file = $request->file("attached_files.$index.file");
                $alt = $fileData['alt_text'] ?? null;

                if (!$file) {
                    throw new \Exception("Archivo no encontrado en el índice $index");
                }

                $mime = $file->getMimeType();
                $type = str_contains($mime, 'image') ? 'image' :
                        (str_contains($mime, 'video') ? 'video' :
                        (str_contains($mime, 'pdf') ? 'pdf' : 'unknown'));

                // Validación por tipo
                switch ($type) {
                    case 'image':
                        $image = $imageManager->read($file->getRealPath());
                        if ($image->width() < 640 || $image->height() < 480) {
                            throw new \Exception('La imagen debe tener al menos 640x480 px.');
                        }
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

                // Almacenar
                $path = $file->store('posts/files', 'public');

                AttachedFile::create([
                    'post_id'       => $post->id,
                    'original_name' => $file->getClientOriginalName(),
                    'file_url'      => $path,
                    'type'          => $type,
                    'alt_text'      => $alt,
                    'options'       => null,
                ]);
            }

            return response()->json([
                'message' => '✅ Post creado con éxito',
                'post' => $post->load(['tags', 'attachedFiles'])
            ], 201);
        });

    } catch (\Throwable $e) {
        Log::error('❌ Error al crear post:', ['error' => $e->getMessage()]);
        return response()->json([
            'message' => 'Error al crear el post',
            'details' => $e->getMessage()
        ], 500);
    }
}





    public function show($id): JsonResponse
    {
        $post = Post::with(['resourceType', 'tags', 'attachedFiles'])->findOrFail($id);
        return response()->json($post);
    }

    public function update(StorePostRequest $request, $id): JsonResponse
{
    return response()->json(['message' => 'Método update pendiente de implementación.']);
}


    public function destroy($id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted (soft delete)']);
    }

    public function restore($id): JsonResponse
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();

        return response()->json(['message' => 'Post restored successfully']);
    }

    public function forceDelete($id): JsonResponse
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->forceDelete();

        return response()->json(['message' => 'Post permanently deleted']);
    }
}
