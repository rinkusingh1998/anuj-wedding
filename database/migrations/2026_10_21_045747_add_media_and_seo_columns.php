<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the media + content columns used by the CMS.
 *
 * Every public entity (page, state, city, service) now stores its own CDN
 * image, an optional OG image and a gallery. Pages also get a flexible JSON
 * "content" column so hero slides, stats, packages, testimonials and FAQs can
 * be edited from the admin panel instead of being hard-coded in Blade.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->string('image')->nullable()->after('intro_content');
            $table->string('og_image')->nullable()->after('image');
            $table->json('gallery')->nullable()->after('og_image');
            $table->json('content')->nullable()->after('gallery');
            $table->json('faqs')->nullable()->after('content');
        });

        Schema::table('states', function (Blueprint $table): void {
            $table->string('image')->nullable()->after('about_content');
            $table->string('og_image')->nullable()->after('image');
            $table->json('gallery')->nullable()->after('og_image');
        });

        Schema::table('cities', function (Blueprint $table): void {
            $table->string('image')->nullable()->after('faqs');
            $table->string('og_image')->nullable()->after('image');
            $table->json('gallery')->nullable()->after('og_image');
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->string('image')->nullable()->after('long_description');
            $table->string('og_image')->nullable()->after('image');
            $table->json('gallery')->nullable()->after('og_image');
            $table->json('content')->nullable()->after('gallery');
        });

        Schema::table('business_profiles', function (Blueprint $table): void {
            $table->string('og_image')->nullable()->after('favicon');
        });
    }

    public function down(): void
    {
        Schema::table('business_profiles', function (Blueprint $table): void {
            $table->dropColumn('og_image');
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->dropColumn(['image', 'og_image', 'gallery', 'content']);
        });

        Schema::table('cities', function (Blueprint $table): void {
            $table->dropColumn(['image', 'og_image', 'gallery']);
        });

        Schema::table('states', function (Blueprint $table): void {
            $table->dropColumn(['image', 'og_image', 'gallery']);
        });

        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn(['image', 'og_image', 'gallery', 'content', 'faqs']);
        });
    }
};
