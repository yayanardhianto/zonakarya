<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestCategory;

class TestCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Screening Test',
                'description' => 'Basic screening test to assess fundamental knowledge and skills',
                'is_active' => true,
            ],
            [
                'name' => 'Psychology Test',
                'description' => 'Psychological assessment to evaluate personality and behavioral traits',
                'is_active' => true,
            ],
            [
                'name' => 'Technical Test',
                'description' => 'Technical skills assessment for specific job requirements',
                'is_active' => true,
            ],
            [
                'name' => 'Language Test',
                'description' => 'Language proficiency test for communication skills',
                'is_active' => true,
            ],
            [
                'name' => 'Aptitude Test',
                'description' => 'General aptitude test to measure cognitive abilities',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            TestCategory::create($category);
        }
    }
}