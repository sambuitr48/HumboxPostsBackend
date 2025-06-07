<?php

namespace App\Http\Controllers\Api;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        $tags = Tag::all()->groupBy('name')->map(function ($group) {
            return $group->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'subcategory' => $tag->subcategory,
                ];
            })->values();
        });

        return response()->json($tags);
    }
}
