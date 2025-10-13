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
        // First, add the column if it doesn't exist
        if (!Schema::hasColumn('job_vacancies', 'unique_code')) {
            Schema::table('job_vacancies', function (Blueprint $table) {
                $table->string('unique_code', 4)->nullable();
            });
        }
        
        // Generate unique codes for existing records that don't have one
        $jobs = \App\Models\JobVacancy::whereNull('unique_code')->orWhere('unique_code', '')->get();
        foreach ($jobs as $job) {
            $job->unique_code = $this->generateUniqueCode();
            $job->save();
        }
        
        // Make it unique and add index if not already exists
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->string('unique_code', 4)->unique()->change();
            
            // Check if index already exists before adding
            $indexes = DB::select("SHOW INDEX FROM job_vacancies WHERE Key_name = 'job_vacancies_unique_code_index'");
            if (empty($indexes)) {
                $table->index('unique_code', 'job_vacancies_unique_code_index');
            }
        });
    }
    
    private function generateUniqueCode()
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        
        do {
            // Generate 2 random letters
            $code = '';
            for ($i = 0; $i < 2; $i++) {
                $code .= $letters[rand(0, strlen($letters) - 1)];
            }
            
            // Generate 2 random numbers
            for ($i = 0; $i < 2; $i++) {
                $code .= $numbers[rand(0, strlen($numbers) - 1)];
            }
            
            // Shuffle the characters to make it non-sequential
            $codeArray = str_split($code);
            shuffle($codeArray);
            $code = implode('', $codeArray);
            
        } while (\App\Models\JobVacancy::where('unique_code', $code)->exists());
        
        return $code;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropIndex(['unique_code']);
            $table->dropColumn('unique_code');
        });
    }
};
