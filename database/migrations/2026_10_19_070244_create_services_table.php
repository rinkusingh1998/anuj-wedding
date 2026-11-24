<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Services list. Yehi services har city page par
     * "{service} in {city}" landing pages banati hain (programmatic SEO).
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->string('name', 140);
            $table->string('slug', 160)->unique();
            $table->string('short_name', 140)->nullable();
            $table->string('icon', 120)->nullable();          // FontAwesome class, jaise fa-solid fa-heart
            $table->string('tagline', 255)->nullable();
            $table->text('description')->nullable();
            $table->longText('long_description')->nullable();

            // SEO fields
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            $table->boolean('is_visible')->default(true);
            $table->unsignedTinyInteger('priority')->default(50);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['is_visible', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
