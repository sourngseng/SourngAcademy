<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('title_km')->nullable(); // Khmer title
            $table->text('content')->nullable();
            $table->text('content_km')->nullable(); // Khmer content
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('icon')->nullable(); // Font Awesome icon class
            $table->boolean('show_in_footer')->default(true);
            $table->boolean('show_in_header')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
