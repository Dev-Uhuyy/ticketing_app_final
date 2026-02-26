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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Bank Transfer, Credit Card, E-Wallet
            $table->string('code')->unique(); // e.g., bank_transfer, credit_card
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // path to icon image
            $table->boolean('is_active')->default(true);
            $table->integer('fee_percentage')->default(0); // additional fee percentage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
