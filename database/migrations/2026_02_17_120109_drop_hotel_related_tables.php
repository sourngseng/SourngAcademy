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
        // Drop foreign keys first if necessary or just drop tables in correct order
        Schema::disableForeignKeyConstraints();
        
        Schema::dropIfExists('booking_rooms');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('amenity_room');
        Schema::dropIfExists('room_images');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('room_types');
        Schema::dropIfExists('guest_reviews');
        Schema::dropIfExists('guest_profiles');
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We do not want to reverse this easily into a hotel system again
        // But for completeness we could leave it empty or recreate tables
    }
};
