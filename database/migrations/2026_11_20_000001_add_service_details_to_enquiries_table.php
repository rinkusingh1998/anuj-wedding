<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Enquiries now keep the selected service and event timing/venue in their own
 * columns so the admin panel can search and filter them.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            if (! Schema::hasColumn('enquiries', 'service')) {
                $table->string('service', 140)->nullable()->after('event_date');
            }

            if (! Schema::hasColumn('enquiries', 'event_time')) {
                $table->string('event_time', 40)->nullable()->after('event_date');
            }

            if (! Schema::hasColumn('enquiries', 'event_address')) {
                $table->string('event_address', 500)->nullable()->after('city');
            }
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn(['service', 'event_time', 'event_address']);
        });
    }
};
