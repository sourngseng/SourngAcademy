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
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->integer('views_count')->default(0)->after('published_at');
            $table->json('reader_locations')->nullable()->after('views_count');
            $table->json('reader_device_types')->nullable()->after('reader_locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn(['views_count', 'reader_locations', 'reader_device_types']);
        });
    }
};
