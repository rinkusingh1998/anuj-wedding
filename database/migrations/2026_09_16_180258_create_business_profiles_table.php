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
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
            // Business Information
            $table->string('business_name');
            $table->string('short_name')->nullable();
            $table->string('tagline')->nullable();

            // Branding
            $table->string('logo')->nullable();
            $table->string('mobile_logo')->nullable();
            $table->string('favicon')->nullable();

            // Contact Information
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->text('google_map_url')->nullable();

            // Social Media
            $table->text('facebook_url')->nullable();
            $table->text('instagram_url')->nullable();
            $table->text('youtube_url')->nullable();

            // Footer
            $table->text('footer_about')->nullable();
            $table->string('footer_copyright')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_profiles');
    }
};
