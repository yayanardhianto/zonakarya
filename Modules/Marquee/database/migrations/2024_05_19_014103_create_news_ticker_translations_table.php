<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Marquee\app\Models\NewsTicker;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('news_ticker_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(NewsTicker::class);
            $table->string('lang_code');
            $table->string('title')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('news_ticker_translations');
    }
};
