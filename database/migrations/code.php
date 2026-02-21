<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('rooms', function (Blueprint $table) {
			$table->id('room_id');
			$table->string('room_number', 10)->unique();
			$table->enum('room_type', ['Single', 'Double', 'Suite', 'Deluxe']);
			$table->decimal('price_per_night', 10, 2);
			$table->enum('status', ['Available', 'Occupied', 'Maintenance', 'Reserved'])->default('Available');
			$table->integer('floor_number');
			$table->integer('max_occupancy');
			$table->text('description')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('rooms');
	}
};