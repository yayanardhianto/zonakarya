<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestQuestion;
use App\Models\TestPackage;

class VideoRecordQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videoQuestions = [
            [
                'question_text' => 'Please record a short video testimonial about your experience with our company and why you would like to work here.',
                'question_type' => 'video_record',
                'points' => 15,
                'order' => 1
            ],
            [
                'question_text' => 'Record a video introducing yourself and explaining your career goals.',
                'question_type' => 'video_record',
                'points' => 10,
                'order' => 2
            ],
            [
                'question_text' => 'Please share a video testimonial about your thoughts on our company culture and values.',
                'question_type' => 'video_record',
                'points' => 12,
                'order' => 3
            ]
        ];

        foreach ($videoQuestions as $questionData) {
            $question = TestQuestion::create($questionData);
            
            // Add to all active test packages
            $packages = TestPackage::where('is_active', true)->get();
            foreach ($packages as $package) {
                $maxOrder = $package->questions()->max('test_package_question.order') ?? 0;
                $package->questions()->attach($question->id, ['order' => $maxOrder + 1]);
            }
        }

        $this->command->info('Video record questions created successfully!');
    }
}