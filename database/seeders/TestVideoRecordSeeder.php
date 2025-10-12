<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestQuestion;
use App\Models\TestPackage;

class TestVideoRecordSeeder extends Seeder
{
    public function run(): void
    {
        // Create video record questions
        $videoQuestions = [
            [
                'question_text' => 'Please record a short video introducing yourself and explaining why you are interested in this position.',
                'question_type' => 'video_record',
                'points' => 15,
                'order' => 1,
            ],
            [
                'question_text' => 'Record a video testimonial about your experience with our application process.',
                'question_type' => 'video_record',
                'points' => 10,
                'order' => 2,
            ],
            [
                'question_text' => 'Share your thoughts on teamwork and collaboration in a brief video.',
                'question_type' => 'video_record',
                'points' => 12,
                'order' => 3,
            ],
        ];

        foreach ($videoQuestions as $questionData) {
            $question = TestQuestion::create($questionData);
            
            // Attach to psychology test package (order 2)
            $psychologyPackage = TestPackage::where('applicant_flow_order', 2)->first();
            if ($psychologyPackage) {
                $psychologyPackage->questions()->attach($question->id, ['order' => $question->order]);
            }
            
            // Also attach to screening test if exists
            $screeningPackage = TestPackage::where('is_screening_test', true)->first();
            if ($screeningPackage) {
                $screeningPackage->questions()->attach($question->id, ['order' => $question->order]);
            }
        }

        $this->command->info('Video record questions created and attached to test packages!');
    }
}