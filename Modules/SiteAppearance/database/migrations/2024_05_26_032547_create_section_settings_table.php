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
        Schema::create('section_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('hero_section')->default(0);
            $table->boolean('about_section')->default(0);
            $table->boolean('faq_section')->default(0);
            $table->boolean('project_section')->default(0);
            $table->boolean('team_section')->default(0);
            $table->boolean('testimonial_section')->default(0);
            $table->boolean('latest_blog_section')->default(0);
            $table->boolean('service_section')->default(0);
            $table->boolean('service_feature_section')->default(0);
            $table->boolean('award_section')->default(0);
            $table->boolean('marquee_section')->default(0);
            $table->boolean('call_to_action_section')->default(0);
            $table->boolean('counter_section')->default(0);
            $table->boolean('choose_us_section')->default(0);
            $table->boolean('pricing_section')->default(0);
            $table->boolean('contact_us_section')->default(0);
            $table->boolean('brands_section')->default(0);
            $table->boolean('banner_section')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_settings');
    }
};
