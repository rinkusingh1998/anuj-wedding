<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extra navigation metadata.
 *
 * `description` is the small subtitle shown under a label (mega menu group
 * headings and mobile menu items). `meta` stores optional presentation flags
 * such as the mega menu group icon or a call to action marker.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->string('description', 255)->nullable()->after('label');
            $table->json('meta')->nullable()->after('group');
        });
    }

    public function down(): void
    {
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->dropColumn(['description', 'meta']);
        });
    }
};
