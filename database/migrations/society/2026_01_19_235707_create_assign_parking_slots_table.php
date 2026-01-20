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
        Schema::create('assign_parking_slots', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('parking_slots_id')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->unsignedBigInteger('residents_id')->nullable();
            $table->date('allocated_from')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys (recommended)
            $table->foreign('parking_slots_id')
                ->references('id')->on('parking_slots')
                ->onDelete('cascade');

            $table->foreign('residents_id')
                ->references('id')->on('residents')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assign_parking_slots');
    }
};
