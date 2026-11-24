<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Decoration packages, testimonials and the global FAQ bank.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->string('name', 160);
            $table->string('slug', 180)->unique();
            $table->string('label', 160)->nullable();
            $table->string('badge', 80)->nullable();
            $table->string('price_from', 60)->nullable();
            $table->string('price_note', 160)->nullable();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->json('content')->nullable();

            $table->string('image')->nullable();
            $table->string('og_image')->nullable();
            $table->json('gallery')->nullable();

            $table->boolean('is_visible')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedTinyInteger('priority')->default(50);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();

            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url', 255)->nullable();
            $table->boolean('robots_index')->default(true);
            $table->boolean('robots_follow')->default(true);
            $table->string('og_title', 255)->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_type', 50)->nullable();
            $table->string('twitter_card', 50)->nullable();
            $table->string('twitter_title', 255)->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image', 255)->nullable();
            $table->string('focus_keyword', 255)->nullable();
            $table->string('image_alt', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();

            $table->string('name', 140);
            $table->string('event', 160)->nullable();
            $table->text('quote');
            $table->string('image')->nullable();
            $table->string('city', 140)->nullable();

            $table->boolean('is_visible')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();

            $table->string('question', 255);
            $table->text('answer');
            $table->string('category', 120)->default('general');
            $table->string('scope', 40)->default('general'); // general | service | package | location | page

            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_visible', 'scope', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('packages');
    }
};
