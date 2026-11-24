<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * City / kasba / gaon ki pages. Har city ka URL hota hai: {state}/{city}
     * aur har service ke liye: {state}/{city}/{service}.
     *
     * is_visible = false karne par wo page turant 404 ho jata hai
     * aur sitemap.xml se bhi hat jata hai.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained()->cascadeOnDelete();

            // Basic information
            $table->string('name', 140);
            $table->string('slug', 160);
            $table->string('type', 20)->default('city');   // city | town | tehsil | village
            $table->string('district', 140)->nullable();   // Jaise "Bulandshahr"
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Visibility / ordering
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_featured')->default(false); // Local belt ke important sheher (Sikandrabad, Khurja...)
            $table->unsignedTinyInteger('priority')->default(50);
            $table->unsignedInteger('sort_order')->default(0);

            // SEO fields (khaali chhodne par smart default text apne aap ban jata hai)
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Page content
            $table->string('heading', 255)->nullable();
            $table->string('sub_heading', 255)->nullable();
            $table->longText('intro_content')->nullable();
            $table->longText('about_content')->nullable();
            $table->json('highlights')->nullable();        // ["400+ Weddings", "Same Day Setup", ...]
            $table->json('faqs')->nullable();              // [{question, answer}, ...]

            $table->timestamps();

            $table->unique(['state_id', 'slug']);
            $table->index(['state_id', 'is_visible', 'sort_order']);
            $table->index(['district']);
            $table->index(['is_visible', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
