<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Blog: categories + posts. Both are fully admin managed (CRUD, SEO,
 * publication status, images, scheduling).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 140);
            $table->string('slug', 160)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

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
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_category_id')->nullable()->constrained('post_categories')->nullOnDelete();

            $table->string('title', 255);
            $table->string('slug', 200)->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('body')->nullable();
            $table->string('author', 120)->nullable();
            $table->unsignedInteger('reading_minutes')->nullable();

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

            $table->index(['is_visible', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_categories');
    }
};
