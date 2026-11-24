<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Locality / area / neighbourhood pages.
 *
 * Hierarchy: State -> City -> Locality.
 * A locality page is only public when it, its city and its state are all
 * visible, so hiding a parent hides the child automatically.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('localities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();

            $table->string('name', 140);
            $table->string('slug', 160);
            $table->string('type', 20)->default('locality'); // locality | area | neighbourhood | village
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->boolean('is_visible')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_service_area')->default(false);
            $table->unsignedTinyInteger('priority')->default(50);
            $table->unsignedInteger('sort_order')->default(0);

            $table->string('heading', 255)->nullable();
            $table->string('sub_heading', 255)->nullable();
            $table->longText('intro_content')->nullable();
            $table->longText('about_content')->nullable();
            $table->json('highlights')->nullable();
            $table->json('faqs')->nullable();
            $table->json('content')->nullable();

            $table->string('image')->nullable();
            $table->string('og_image')->nullable();
            $table->json('gallery')->nullable();

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
            $table->string('eyebrow', 160)->nullable();
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['city_id', 'slug']);
            $table->index(['state_id', 'is_visible']);
            $table->index(['city_id', 'is_visible', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('localities');
    }
};
