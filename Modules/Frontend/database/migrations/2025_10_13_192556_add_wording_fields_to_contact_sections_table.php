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
        Schema::table('contact_sections', function (Blueprint $table) {
            // Wording fields for contact page
            $table->string('headquarters_title')->nullable()->after('map');
            $table->string('email_title')->nullable()->after('headquarters_title');
            $table->string('phone_title')->nullable()->after('email_title');
            $table->string('get_direction_text')->nullable()->after('phone_title');
            $table->string('send_message_text')->nullable()->after('get_direction_text');
            $table->string('call_anytime_text')->nullable()->after('send_message_text');
            
            // Form wording fields
            $table->string('form_title')->nullable()->after('call_anytime_text');
            $table->string('form_subtitle')->nullable()->after('form_title');
            $table->string('full_name_label')->nullable()->after('form_subtitle');
            $table->string('email_label')->nullable()->after('full_name_label');
            $table->string('website_label')->nullable()->after('email_label');
            $table->string('subject_label')->nullable()->after('website_label');
            $table->string('message_label')->nullable()->after('subject_label');
            $table->string('submit_button_text')->nullable()->after('message_label');
            
            // Form visibility settings
            $table->boolean('show_website_field')->default(true)->after('submit_button_text');
            $table->boolean('show_second_phone')->default(true)->after('show_website_field');
            $table->boolean('show_second_email')->default(true)->after('show_second_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_sections', function (Blueprint $table) {
            $table->dropColumn([
                'headquarters_title',
                'email_title', 
                'phone_title',
                'get_direction_text',
                'send_message_text',
                'call_anytime_text',
                'form_title',
                'form_subtitle',
                'full_name_label',
                'email_label',
                'website_label',
                'subject_label',
                'message_label',
                'submit_button_text',
                'show_website_field',
                'show_second_phone',
                'show_second_email'
            ]);
        });
    }
};