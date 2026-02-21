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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_id')->constrained('campuses')->onDelete('cascade');
            $table->string('room_number');
            $table->string('name')->nullable(); // e.g. "Lab 101"
            $table->string('building')->nullable(); // e.g. "Main Building"
            $table->string('floor')->nullable(); // e.g. "First Floor"
            $table->integer('capacity')->nullable();
            $table->enum('type', ['Lecture Hall', 'Lab', 'Classroom', 'Online'])->default('Classroom');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
