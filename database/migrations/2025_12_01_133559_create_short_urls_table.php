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
        Schema::create('short_urls', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., 'abc123'
            $table->longText('original_url'); // Full URL to redirect to
            $table->unsignedBigInteger('created_by')->nullable(); // Admin who created it
            $table->unsignedInteger('click_count')->default(0); // Track clicks
            $table->timestamps();
            $table->softDeletes(); // Allow soft delete

            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_urls');
    }
};
