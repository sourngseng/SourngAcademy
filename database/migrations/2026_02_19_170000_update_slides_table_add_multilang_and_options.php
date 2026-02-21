<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            // Add description if not exists
            if (!Schema::hasColumn('slides', 'description')) {
                $table->text('description')->nullable()->after('subtitle');
            }
            
            // Add button_text if not exists
            if (!Schema::hasColumn('slides', 'button_text')) {
                $table->string('button_text')->nullable()->after('link_url');
            }

            // Add boolean flags
            if (!Schema::hasColumn('slides', 'show_title')) {
                $table->boolean('show_title')->default(true)->after('is_active');
            }
            if (!Schema::hasColumn('slides', 'show_subtitle')) {
                $table->boolean('show_subtitle')->default(true)->after('show_title');
            }
            if (!Schema::hasColumn('slides', 'show_description')) {
                $table->boolean('show_description')->default(true)->after('show_subtitle');
            }
            if (!Schema::hasColumn('slides', 'show_button')) {
                $table->boolean('show_button')->default(true)->after('show_description');
            }
        });

        // Change existing columns to TEXT to support JSON length safely
        // Using raw statement to avoid doctrine/dbal dependency
        try {
            DB::statement('ALTER TABLE slides MODIFY title TEXT');
            DB::statement('ALTER TABLE slides MODIFY subtitle TEXT');
            DB::statement('ALTER TABLE slides MODIFY button_text TEXT');
        } catch (\Exception $e) {
            // Ignore if fails (e.g. SQLite uses different syntax, or permissions issue)
            // But assume MySQL/MariaDB for this environment (Laragon)
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            if (Schema::hasColumn('slides', 'show_title')) $table->dropColumn('show_title');
            if (Schema::hasColumn('slides', 'show_subtitle')) $table->dropColumn('show_subtitle');
            if (Schema::hasColumn('slides', 'show_description')) $table->dropColumn('show_description');
            if (Schema::hasColumn('slides', 'show_button')) $table->dropColumn('show_button');
            if (Schema::hasColumn('slides', 'button_text')) $table->dropColumn('button_text');
            if (Schema::hasColumn('slides', 'description')) $table->dropColumn('description');
        });
        
        // Creating separate statement for altering columns back if needed, but risky. 
        // We leave them as TEXT since it's compatible.
    }
};
