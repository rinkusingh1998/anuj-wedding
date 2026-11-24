<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ek "state" (jaise Uttar Pradesh) ki SEO landing page ki settings.
     * State ko hide karne par us state ke saare city pages automatic band (404) ho jate hain.
     */
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();

            // Basic information
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->string('region', 120)->nullable();          // Jaise "Delhi NCR", "Purvanchal"
            $table->boolean('is_visible')->default(true);       // Google / sitemap ke liye on-off switch
            $table->boolean('is_default')->default(false);      // Main service area (Uttar Pradesh)
            $table->unsignedTinyInteger('priority')->default(50); // Sitemap priority (0.1 se 1.0 ke liye 10-100)
            $table->unsignedInteger('sort_order')->default(0);

            // SEO fields (khaali chhodne par smart default text apne aap ban jata hai)
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Page content
            $table->string('heading', 255)->nullable();
            $table->string('sub_heading', 255)->nullable();
            $table->longText('intro_content')->nullable();
            $table->longText('about_content')->nullable();
            $table->json('faqs')->nullable();                   // [{question, answer}, ...]

            $table->timestamps();

            $table->index(['is_visible', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
