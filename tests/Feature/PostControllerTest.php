<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\ResourceType;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_posts()
    {
        // crea
        $user = User::factory()->create();

        // crea
        $resourceType = ResourceType::factory()->create();

        // Crea 2 posts asociados al usuario y al tipo de recurso
        Post::factory()->count(2)->create([
            'user_id' => $user->id,
            'resource_type_id' => $resourceType->id,
        ]);

        // hace petición al endpoint
        $response = $this->actingAs($user)->getJson('/api/posts');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'title', 'description', 'location', 'user_id', 'resource_type_id']
                 ]);
    }
}