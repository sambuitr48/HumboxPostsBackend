<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Ingeniería de Software
            ['name' => 'Software Engineering', 'subcategory' => 'Frontend'],
            ['name' => 'Software Engineering', 'subcategory' => 'Backend'],
            ['name' => 'Software Engineering', 'subcategory' => 'DevOps'],
            ['name' => 'Software Engineering', 'subcategory' => 'Mobile'],
            ['name' => 'Software Engineering', 'subcategory' => 'Testing'],

            // Ingeniería Industrial
            ['name' => 'Industrial Engineering', 'subcategory' => 'Process Optimization'],
            ['name' => 'Industrial Engineering', 'subcategory' => 'Logistics'],
            ['name' => 'Industrial Engineering', 'subcategory' => 'Productivity'],
            ['name' => 'Industrial Engineering', 'subcategory' => 'Quality Control'],

            // Ingeniería Civil
            ['name' => 'Civil Engineering', 'subcategory' => 'Structures'],
            ['name' => 'Civil Engineering', 'subcategory' => 'Construction'],
            ['name' => 'Civil Engineering', 'subcategory' => 'Hydraulics'],
            ['name' => 'Civil Engineering', 'subcategory' => 'Geotechnics'],

            // Turismo
            ['name' => 'Tourism', 'subcategory' => 'Ecotourism'],
            ['name' => 'Tourism', 'subcategory' => 'Hotel Management'],
            ['name' => 'Tourism', 'subcategory' => 'Cultural Tourism'],
            ['name' => 'Tourism', 'subcategory' => 'Travel Agencies'],

            // Administración de Empresas
            ['name' => 'Business Administration', 'subcategory' => 'Finance'],
            ['name' => 'Business Administration', 'subcategory' => 'Marketing'],
            ['name' => 'Business Administration', 'subcategory' => 'Human Resources'],
            ['name' => 'Business Administration', 'subcategory' => 'Entrepreneurship'],

            // Psicología
            ['name' => 'Psychology', 'subcategory' => 'Clinical'],
            ['name' => 'Psychology', 'subcategory' => 'Organizational'],
            ['name' => 'Psychology', 'subcategory' => 'Developmental'],
            ['name' => 'Psychology', 'subcategory' => 'Neuropsychology'],

            // Derecho
            ['name' => 'Law', 'subcategory' => 'Criminal'],
            ['name' => 'Law', 'subcategory' => 'Civil'],
            ['name' => 'Law', 'subcategory' => 'Labor'],
            ['name' => 'Law', 'subcategory' => 'Human Rights'],

            // Medicina
            ['name' => 'Medicine', 'subcategory' => 'Neurology'],
            ['name' => 'Medicine', 'subcategory' => 'Pediatrics'],
            ['name' => 'Medicine', 'subcategory' => 'Internal Medicine'],
            ['name' => 'Medicine', 'subcategory' => 'Cardiology'],

            // Enfermería
            ['name' => 'Nursing', 'subcategory' => 'Geriatrics'],
            ['name' => 'Nursing', 'subcategory' => 'Pediatrics'],
            ['name' => 'Nursing', 'subcategory' => 'Community Health'],
            ['name' => 'Nursing', 'subcategory' => 'Critical Care'],

            // Marketing Digital
            ['name' => 'Digital Marketing', 'subcategory' => 'Social Media'],
            ['name' => 'Digital Marketing', 'subcategory' => 'SEO/SEM'],
            ['name' => 'Digital Marketing', 'subcategory' => 'Email Marketing'],
            ['name' => 'Digital Marketing', 'subcategory' => 'Content Strategy'],
        ];

        DB::table('tags')->insert($tags);
    }
}
