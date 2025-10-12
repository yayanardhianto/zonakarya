<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('test_package_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_package_id')->constrained('test_packages')->onDelete('cascade');
            $table->foreignId('test_question_id')->constrained('test_questions')->onDelete('cascade');
            $table->integer('order')->default(0); // Order of question in this package
            $table->timestamps();
            
            // Ensure unique combination
            $table->unique(['test_package_id', 'test_question_id']);
            
            // Index for performance
            $table->index(['test_package_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_package_question');
    }
};