<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestQuestion;
use App\Models\TestPackage;

class ForcedChoiceQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the highest order from existing questions in package 1
        $package = TestPackage::find(1);
        $maxOrder = 0;
        if ($package) {
            $maxOrder = $package->questions()->max('test_package_question.order') ?? 0;
        }

        // Create forced choice questions
        $forcedChoiceQuestions = [
            [
                'question_text' => json_encode([
                    'Gampangan, Mudah Setuju',
                    'Percaya, Mudah Percaya Pada Orang Lain',
                    'Petualang, Mengambil Resiko',
                    'Toleran, Menghormati'
                ]),
                'question_type' => 'forced_choice',
                'points' => 10,
                'order' => $maxOrder + 1
            ],
            [
                'question_text' => json_encode([
                    'Kreatif, Inovatif',
                    'Analitis, Logis',
                    'Sosial, Ramah',
                    'Mandiri, Independen'
                ]),
                'question_type' => 'forced_choice',
                'points' => 10,
                'order' => $maxOrder + 2
            ],
            [
                'question_text' => json_encode([
                    'Perfeksionis, Detail',
                    'Fleksibel, Adaptif',
                    'Kompetitif, Ambisius',
                    'Kolaboratif, Team Player'
                ]),
                'question_type' => 'forced_choice',
                'points' => 10,
                'order' => $maxOrder + 3
            ]
        ];

        foreach ($forcedChoiceQuestions as $questionData) {
            TestQuestion::create($questionData);
        }

        // Attach forced choice questions to test package ID 1
        $package = TestPackage::find(1);
        if ($package) {
            $forcedChoiceQuestions = TestQuestion::where('question_type', 'forced_choice')->get();
            
            echo "Found {$forcedChoiceQuestions->count()} forced choice questions\n";
            echo "Attaching to package: {$package->name}\n";
            
            foreach ($forcedChoiceQuestions as $question) {
                // Check if already attached
                if (!$package->questions()->where('test_question_id', $question->id)->exists()) {
                    $package->questions()->attach($question->id, [
                        'order' => $question->order,
                        'time_per_question_seconds' => 120 // 2 minutes per question
                    ]);
                    echo "Attached question ID {$question->id}\n";
                } else {
                    echo "Question ID {$question->id} already attached\n";
                }
            }
            
            // Update total questions count
            $package->updateTotalQuestions();
            echo "Updated total questions count: {$package->fresh()->total_questions}\n";
        } else {
            echo "Package with ID 1 not found\n";
        }

        $this->command->info('Forced choice questions created and attached to test package successfully!');
    }
}