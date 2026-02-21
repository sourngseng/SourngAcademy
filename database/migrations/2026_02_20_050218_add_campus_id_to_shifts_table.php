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
        Schema::table('shifts', function (Blueprint $table) {
            $table->unsignedBigInteger('campus_id')->nullable()->after('id');
            // Assuming 'campuses' table exists. Check if foreign key constraint is needed. 
            // Usually yes, but user sometimes doesn't want strict foreign keys. Let's add it for safety.
            // Wait, does campuses exist? Yes, I saw code referencing it.
            // If campuses table name is 'campuses', then:
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
            $table->dropColumn('campus_id');
        });
    }
};
