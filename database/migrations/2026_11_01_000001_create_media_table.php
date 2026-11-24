<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Central media library.
 *
 * Every uploaded file gets one row here. Public models keep storing the
 * file path / URL in their own column (so existing records keep working),
 * but the library is the single place where an admin can upload, replace,
 * search, re-use and safely delete an image.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            $table->string('disk', 40)->default('public');
            $table->string('path')->nullable();          // storage path, e.g. "pages/abc.jpg"
            $table->string('url')->nullable();           // external / CDN URL
            $table->string('filename')->nullable();
            $table->string('mime_type', 120)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();

            $table->string('alt_text', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('caption')->nullable();

            $table->string('collection', 60)->default('general'); // page, service, city, blog ...
            $table->string('folder', 120)->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['collection', 'folder']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
