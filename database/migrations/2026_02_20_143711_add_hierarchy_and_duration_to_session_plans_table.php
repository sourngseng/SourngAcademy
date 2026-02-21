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
        Schema::table('session_plans', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('session_plans')->onDelete('cascade');
            $table->integer('duration')->nullable();
            $table->enum('duration_unit', ['minutes', 'hours'])->default('minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_plans', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'duration', 'duration_unit']);
        });
    }
};
