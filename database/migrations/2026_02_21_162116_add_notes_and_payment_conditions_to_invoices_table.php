<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->text('payment_notes')->nullable()->after('status')
                  ->comment('Special notes shown to student about payment (e.g. bank account, contact)');
            $table->text('payment_conditions')->nullable()->after('payment_notes')
                  ->comment('Terms and conditions for payment (e.g. late fees, deadlines)');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['payment_notes', 'payment_conditions']);
        });
    }
};
