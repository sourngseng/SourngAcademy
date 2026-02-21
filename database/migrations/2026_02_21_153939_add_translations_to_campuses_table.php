<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campuses', function (Blueprint $table) {
            // JSON column for all locale translations of name & description
            $table->json('translations')->nullable()->after('is_active')
                  ->comment('{"en":{"name":"...","description":"..."},"km":{"name":"...","description":"..."}}');
        });
    }

    public function down(): void
    {
        Schema::table('campuses', function (Blueprint $table) {
            $table->dropColumn('translations');
        });
    }
};
