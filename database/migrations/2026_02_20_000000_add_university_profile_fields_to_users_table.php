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
        Schema::table('users', function (Blueprint $table) {
            $table->string('name_kh')->nullable()->after('name');
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable()->after('name_kh');
            $table->date('dob')->nullable()->after('gender');
            $table->string('phone')->nullable()->after('dob');
            $table->text('address')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['name_kh', 'gender', 'dob', 'phone', 'address']);
        });
    }
};
