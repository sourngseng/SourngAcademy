<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campus_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_id')->constrained('campuses')->onDelete('cascade');

            // Type of payment method (bank_transfer, qr_code, paypal, stripe, etc.)
            $table->string('type')->default('bank_transfer');
            // Display name shown to student
            $table->string('name');
            // Icon class or emoji
            $table->string('icon')->nullable()->default('fas fa-university');
            // Official bank/provider name
            $table->string('provider_name')->nullable();
            // Account number / email / wallet ID
            $table->string('account_number')->nullable();
            // Account holder name
            $table->string('account_name')->nullable();
            // Currency (USD, KHR, EUR, etc.)
            $table->string('currency', 10)->default('USD');
            // QR image path (for KHQR / QR-based banks)
            $table->string('qr_image')->nullable();
            // PayPal email / Stripe public key / any external link
            $table->string('gateway_link')->nullable();
            // Extra instructions shown to student
            $table->text('instructions')->nullable();
            // Hex color for the card badge
            $table->string('color', 20)->nullable()->default('#007bff');
            // Brand logo image path
            $table->string('logo_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campus_payment_methods');
    }
};
