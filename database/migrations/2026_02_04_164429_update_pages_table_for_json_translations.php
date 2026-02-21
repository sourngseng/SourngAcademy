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
        // First, migrate existing data to JSON format
        $pages = DB::table('pages')->get();
        
        foreach ($pages as $page) {
            $titles = ['en' => $page->title];
            $contents = ['en' => $page->content];
            
            // Add Khmer if exists
            if (!empty($page->title_km)) {
                $titles['km'] = $page->title_km;
            }
            if (!empty($page->content_km)) {
                $contents['km'] = $page->content_km;
            }
            
            DB::table('pages')
                ->where('id', $page->id)
                ->update([
                    'title' => json_encode($titles, JSON_UNESCAPED_UNICODE),
                    'content' => json_encode($contents, JSON_UNESCAPED_UNICODE),
                ]);
        }
        
        // Remove old columns
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['title_km', 'content_km']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('title_km')->nullable()->after('title');
            $table->text('content_km')->nullable()->after('content');
        });
    }
};
