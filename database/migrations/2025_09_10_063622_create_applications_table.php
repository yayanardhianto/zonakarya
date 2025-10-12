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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_vacancy_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_session_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'sent', 'check', 'short_call', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('test_sent_at')->nullable();
            $table->timestamp('test_completed_at')->nullable();
            $table->integer('test_score')->nullable();
            $table->text('whatsapp_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
