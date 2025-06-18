<?php

namespace Database\Seeders;

use App\Models\ResourceType;
use Illuminate\Database\Seeder;

class ResourceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Tutorial'],
            ['name' => 'Video'],
            ['name' => 'Document'],
            ['name' => 'Study Material'],
            ['name' => 'Book'],
            ['name' => 'Event'],
            ['name' => 'News'],
            ['name' => 'Workshop'],
            ['name' => 'Webinar'],
            ['name' => 'Conference'],
        ];

        foreach ($types as $type) {
            ResourceType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
