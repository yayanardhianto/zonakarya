<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing questions to pivot table
        $questions = DB::table('test_questions')->get();
        
        foreach ($questions as $question) {
            DB::table('test_package_question')->insert([
                'test_package_id' => $question->package_id,
                'test_question_id' => $question->id,
                'order' => $question->order,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all data from pivot table
        DB::table('test_package_question')->truncate();
    }
};