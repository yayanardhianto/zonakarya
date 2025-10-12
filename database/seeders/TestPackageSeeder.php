<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestPackage;
use App\Models\TestCategory;

class TestPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $screeningCategory = TestCategory::where('name', 'Screening Test')->first();
        $psychologyCategory = TestCategory::where('name', 'Psychology Test')->first();
        $technicalCategory = TestCategory::where('name', 'Technical Test')->first();
        $languageCategory = TestCategory::where('name', 'Language Test')->first();
        $aptitudeCategory = TestCategory::where('name', 'Aptitude Test')->first();

        $packages = [
            [
                'category_id' => $screeningCategory->id,
                'name' => 'Basic Screening Test',
                'description' => 'Comprehensive screening test covering general knowledge, logic, and basic skills',
                'duration_minutes' => 60,
                'total_questions' => 30,
                'passing_score' => 70,
                'is_active' => true,
            ],
            [
                'category_id' => $psychologyCategory->id,
                'name' => 'Personality Assessment',
                'description' => 'Personality test to evaluate behavioral patterns and work style preferences',
                'duration_minutes' => 45,
                'total_questions' => 25,
                'passing_score' => 60,
                'is_active' => true,
            ],
            [
                'category_id' => $technicalCategory->id,
                'name' => 'Programming Fundamentals',
                'description' => 'Technical test covering programming concepts, algorithms, and problem-solving',
                'duration_minutes' => 90,
                'total_questions' => 40,
                'passing_score' => 75,
                'is_active' => true,
            ],
            [
                'category_id' => $languageCategory->id,
                'name' => 'English Proficiency Test',
                'description' => 'English language test covering grammar, vocabulary, and comprehension',
                'duration_minutes' => 50,
                'total_questions' => 35,
                'passing_score' => 65,
                'is_active' => true,
            ],
            [
                'category_id' => $aptitudeCategory->id,
                'name' => 'Logical Reasoning Test',
                'description' => 'Aptitude test focusing on logical thinking, pattern recognition, and analytical skills',
                'duration_minutes' => 75,
                'total_questions' => 50,
                'passing_score' => 70,
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            TestPackage::create($package);
        }
    }
}