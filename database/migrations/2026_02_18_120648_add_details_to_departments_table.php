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
        Schema::table('departments', function (Blueprint $blueprint) {
            $blueprint->string('slug')->nullable()->after('name');
            $blueprint->string('feature_image')->nullable()->after('code');
            $blueprint->text('description')->nullable()->after('feature_image');
            $blueprint->text('mission')->nullable()->after('description');
            $blueprint->text('vision')->nullable()->after('mission');
            $blueprint->text('goal')->nullable()->after('vision');
            $blueprint->json('translations')->nullable()->after('goal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['slug', 'feature_image', 'description', 'mission', 'vision', 'goal', 'translations']);
        });
    }
};
