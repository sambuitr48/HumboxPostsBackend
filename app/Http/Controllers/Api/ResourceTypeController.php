<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ResourceType;
use Illuminate\Http\JsonResponse;

class ResourceTypeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ResourceType::all(['id', 'name']));
    }
}
