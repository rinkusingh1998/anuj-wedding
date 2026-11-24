<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Navigation menus, admin managed redirects and website enquiries.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('navigation_items')->cascadeOnDelete();

            $table->string('menu', 40)->default('header'); // header | footer | mobile
            $table->string('label', 120);
            $table->string('url', 255)->nullable();
            $table->string('route', 120)->nullable();
            $table->string('icon', 120)->nullable();
            $table->string('badge', 60)->nullable();
            $table->string('target', 20)->default('_self');
            $table->string('group', 120)->nullable();      // footer column heading

            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['menu', 'is_visible', 'sort_order']);
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->id();

            $table->string('from_path', 255)->unique();
            $table->string('to_path', 255);
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('hits')->default(0);
            $table->string('note', 255)->nullable();

            $table->timestamps();
        });

        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();

            $table->string('name', 140);
            $table->string('phone', 40);
            $table->string('email', 180)->nullable();
            $table->string('event_type', 120)->nullable();
            $table->string('event_date', 40)->nullable();
            $table->string('city', 140)->nullable();
            $table->string('budget', 80)->nullable();
            $table->text('message')->nullable();

            $table->string('source_page', 255)->nullable();
            $table->string('source_url', 500)->nullable();
            $table->string('status', 30)->default('new'); // new | contacted | converted | closed
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('navigation_items');
    }
};
