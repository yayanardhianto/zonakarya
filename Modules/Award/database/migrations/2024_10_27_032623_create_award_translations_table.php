<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Award\app\Models\Award;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('award_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Award::class);
            $table->string('lang_code');
            $table->string('year')->nullable();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('tag')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('award_translations');
    }
};
