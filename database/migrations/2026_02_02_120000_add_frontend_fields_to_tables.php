<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add fields needed for frontend display
     */
    public function up(): void
    {
        // Add description field to slides table if missing
        if (Schema::hasTable('slides')) {
            Schema::table('slides', function (Blueprint $table) {
                if (!Schema::hasColumn('slides', 'description')) {
                    $table->text('description')->nullable()->after('subtitle');
                }
                if (!Schema::hasColumn('slides', 'button_text')) {
                    $table->string('button_text')->nullable()->after('link_url');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('slides')) {
            Schema::table('slides', function (Blueprint $table) {
                if (Schema::hasColumn('slides', 'description')) {
                    $table->dropColumn('description');
                }
                if (Schema::hasColumn('slides', 'button_text')) {
                    $table->dropColumn('button_text');
                }
            });
        }
    }
};
