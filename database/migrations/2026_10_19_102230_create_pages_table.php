<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Website ke normal static pages (Home, About Us, Services, Gallery,
     * Contact Us, Legal pages waghera) ki SEO settings.
     *
     * Ye table har page ko "dynamic" banata hai:
     *   - Meta title / description / keywords admin panel se badle ja sakte hain.
     *   - Page ko hide karne par wo Google ko "noindex" milta hai aur sitemap se hat jata hai.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            // Page ki pehchaan
            $table->string('name', 120);                       // Admin panel me dikhne wala naam (jaise "About Us")
            $table->string('slug', 160)->unique();             // URL path bina slash ke (jaise "about-us", home ke liye "home")

            // SEO meta
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Hero content (khaali chhodne par template ka default text dikhta hai)
            $table->string('heading', 255)->nullable();
            $table->string('sub_heading', 255)->nullable();
            $table->longText('intro_content')->nullable();

            // Visibility + sitemap
            $table->boolean('is_visible')->default(true);       // OFF = noindex + sitemap se hat jata hai
            $table->unsignedTinyInteger('priority')->default(50);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['is_visible', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
