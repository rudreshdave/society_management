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
        Schema::create('resident_vehicles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('resident_id');
            $table->string('vehicle_registration_no')->nullable();
            $table->date('vehicle_registration_date')->nullable();
            $table->string('vehicle_chasis_no')->nullable();
            $table->string('vehicle_engine_no')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_class')->nullable();
            $table->string('fuel_type')->nullable();
            $table->unsignedBigInteger('parking_slot_id')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
            $table->foreign('resident_id')
                ->references('id')->on('residents')
                ->onDelete('cascade');
            $table->foreign('parking_slot_id')
                ->references('id')->on('parking_slots')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_vehicles');
    }
};
