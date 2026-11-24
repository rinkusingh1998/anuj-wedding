<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 140);
            $table->string('slug', 160)->unique();
            $table->string('icon', 120)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_visible', 'sort_order']);
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->foreignId('service_category_id')
                ->nullable()
                ->after('id')
                ->constrained('service_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('service_category_id');
        });

        Schema::dropIfExists('service_categories');
    }
};
