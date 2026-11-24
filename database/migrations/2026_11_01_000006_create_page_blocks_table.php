<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reusable page content blocks.
 *
 * Each block belongs to a page and holds a small JSON payload (heading, text,
 * button label/url, image, alt text ...). The Blade templates read blocks by
 * key, so an admin can edit / reorder / hide any block - or add a completely
 * new section - without touching code.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();

            $table->string('key', 120);          // e.g. "about.story", "gallery.intro"
            $table->string('type', 40)->default('text'); // text | hero | cta | gallery | html | list
            $table->string('label', 160)->nullable();
            $table->json('fields')->nullable();

            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['page_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_blocks');
    }
};
