<?php

use Illuminate\Support\Facades\Schema;
use Modules\Location\app\Models\Country;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');


            $table->string('gender')->nullable();
            $table->foreignIdFor(Country::class);
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('address')->nullable();

            $table->string('age')->nullable();
            $table->string('phone')->nullable();
            $table->string('image')->nullable();
            $table->string('is_banned')->default('no');
            $table->string('status')->default('active');
            $table->string('verification_token')->nullable();
            $table->string('forget_password_token')->nullable();


            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
