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
        Schema::create('monthly_rates', function (Blueprint $table) {
            $table->id();
            // Type of charge
            $table->enum('rate_type', ['maintenance', 'extra_parking', 'other_charge'])->nullable();
            $table->enum('charge_type', ['Fixed', 'Sqft-based'])->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->date('effective_from')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_rates');
    }
};
