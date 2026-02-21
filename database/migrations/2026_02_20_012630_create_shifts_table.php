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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Morning', 'Afternoon'
            // We can store sessions as JSON or simple start/end if users define sessions elsewhere.
            // But the request implies "Shift" has sessions like Session 1, 2, 3...
            // Or maybe "Shift" is just a label for a time block?
            // "create classes with Timetable... add subjects by sessions... add with shifts selected"
            // Let's assume a Shift has multiple Sessions.
            // Actually, usually a Shift (Morning) has a time range, and Sessions are slots within it.
            // Let's stick to simple Shift definition first: Name, Description?.
            // The user wants to "Create classes with Timetable... add subjects by sessions... add with shifts selected".
            // So a Shift might just be a preset holder.
            // But wait, the previous code had `shiftPresets` in JS.
            // Let's make Shift model capable of storing those presets.
            // name: "Morning", sessions: JSON [{'name':'Session 1', 'start':'07:00'..}]
            $table->json('sessions')->nullable(); // Store array of sessions: [{name, start, end}]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
