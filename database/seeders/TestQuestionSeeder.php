<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestQuestion;
use App\Models\TestPackage;
use App\Models\TestQuestionOption;

class TestQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $basicScreening = TestPackage::where('name', 'Basic Screening Test')->first();
        $programmingTest = TestPackage::where('name', 'Programming Fundamentals')->first();
        $englishTest = TestPackage::where('name', 'English Proficiency Test')->first();

        // Basic Screening Test Questions
        $this->createMultipleChoiceQuestion($basicScreening->id, 1, 
            'What is the capital of Indonesia?', 1, [
                ['Jakarta', true],
                ['Surabaya', false],
                ['Bandung', false],
                ['Medan', false],
            ]
        );

        $this->createMultipleChoiceQuestion($basicScreening->id, 2,
            'Which of the following is a prime number?', 1, [
                ['4', false],
                ['7', true],
                ['8', false],
                ['9', false],
            ]
        );

        $this->createMultipleChoiceQuestion($basicScreening->id, 3,
            'What does CPU stand for?', 1, [
                ['Central Processing Unit', true],
                ['Computer Processing Unit', false],
                ['Central Program Unit', false],
                ['Computer Program Unit', false],
            ]
        );

        // Programming Test Questions
        $this->createMultipleChoiceQuestion($programmingTest->id, 1,
            'What is the time complexity of binary search?', 2, [
                ['O(n)', false],
                ['O(log n)', true],
                ['O(n²)', false],
                ['O(1)', false],
            ]
        );

        $this->createMultipleChoiceQuestion($programmingTest->id, 2,
            'Which data structure follows LIFO principle?', 2, [
                ['Queue', false],
                ['Stack', true],
                ['Array', false],
                ['Linked List', false],
            ]
        );

        $this->createEssayQuestion($programmingTest->id, 3,
            'Explain the difference between pass by value and pass by reference in programming. Provide examples.', 5
        );

        // English Test Questions
        $this->createMultipleChoiceQuestion($englishTest->id, 1,
            'Choose the correct sentence:', 1, [
                ['I am going to school', true],
                ['I is going to school', false],
                ['I are going to school', false],
                ['I be going to school', false],
            ]
        );

        $this->createMultipleChoiceQuestion($englishTest->id, 2,
            'What is the synonym of "beautiful"?', 1, [
                ['Ugly', false],
                ['Pretty', true],
                ['Bad', false],
                ['Small', false],
            ]
        );

        $this->createEssayQuestion($englishTest->id, 3,
            'Write a short paragraph (50-100 words) about your favorite hobby and why you enjoy it.', 3
        );
    }

    private function createMultipleChoiceQuestion($packageId, $order, $questionText, $points, $options)
    {
        $question = TestQuestion::create([
            'package_id' => $packageId,
            'question_text' => $questionText,
            'question_type' => 'multiple_choice',
            'points' => $points,
            'order' => $order,
        ]);

        foreach ($options as $index => $option) {
            TestQuestionOption::create([
                'question_id' => $question->id,
                'option_text' => $option[0],
                'is_correct' => $option[1],
                'order' => $index + 1,
            ]);
        }
    }

    private function createEssayQuestion($packageId, $order, $questionText, $points)
    {
        TestQuestion::create([
            'package_id' => $packageId,
            'question_text' => $questionText,
            'question_type' => 'essay',
            'points' => $points,
            'order' => $order,
        ]);
    }
}