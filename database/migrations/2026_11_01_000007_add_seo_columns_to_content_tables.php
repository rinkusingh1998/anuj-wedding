<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the shared SEO / publication / recovery columns to every public
 * content table, plus the "active service area" flag on cities.
 *
 * Column set is intentionally identical on every table so one reusable trait
 * (App\Support\Concerns\HasSeo) and one reusable Filament schema
 * (App\Filament\Forms\SeoFields) can drive all of them.
 */
return new class extends Migration
{
    /** @var list<string> */
    private const TABLES = ['pages', 'states', 'cities', 'services'];

    public function up(): void
    {
        foreach (self::TABLES as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
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
                $table->softDeletes();
            });
        }

        Schema::table('cities', function (Blueprint $table): void {
            $table->boolean('is_service_area')->default(false)->after('is_featured');
            $table->json('content')->nullable()->after('faqs');
            $table->index(['is_service_area', 'is_visible']);
        });

        Schema::table('states', function (Blueprint $table): void {
            $table->json('content')->nullable()->after('faqs');
        });

        Schema::table('pages', function (Blueprint $table): void {
            $table->string('template', 60)->default('default')->after('slug');
        });

        $this->flagPrimaryServiceAreas();
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn('template');
        });

        Schema::table('cities', function (Blueprint $table): void {
            $table->dropIndex(['is_service_area', 'is_visible']);
            $table->dropColumn(['is_service_area', 'content']);
        });

        Schema::table('states', function (Blueprint $table): void {
            $table->dropColumn('content');
        });

        foreach (self::TABLES as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropSoftDeletes();
                $table->dropColumn([
                    'canonical_url',
                    'robots_index',
                    'robots_follow',
                    'og_title',
                    'og_description',
                    'og_type',
                    'twitter_card',
                    'twitter_title',
                    'twitter_description',
                    'twitter_image',
                    'focus_keyword',
                    'image_alt',
                    'eyebrow',
                    'published_at',
                ]);
            });
        }
    }

    /**
     * The eight primary service regions. Gautam Buddh Nagar is represented by
     * its two cities (Noida and Greater Noida), which share that district.
     */
    private function flagPrimaryServiceAreas(): void
    {
        $names = [
            'Agra',
            'Aligarh',
            'Bulandshahr',
            'Ghaziabad',
            'Hapur',
            'Meerut',
            'Mathura',
            'Noida',
            'Greater Noida',
        ];

        DB::table('cities')->whereIn('name', $names)->update([
            'is_service_area' => true,
            'is_visible' => true,
        ]);
    }
};
