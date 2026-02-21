<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change payment_method from enum to string to support dynamic methods
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method VARCHAR(255) NOT NULL DEFAULT 'Cash'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('Cash','Bank Transfer','KHQR','Stripe','Other') NOT NULL DEFAULT 'Cash'");
    }
};
