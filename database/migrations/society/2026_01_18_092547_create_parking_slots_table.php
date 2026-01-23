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
        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id();
            $table->string('slot_no')->unique();

            // 1 = Car, 2 = Bike
            $table->unsignedTinyInteger('slot_type')->comment('1=Car, 2=Bike')->nullable();

            // 1 = Basement, 2 = Ground
            $table->unsignedTinyInteger('location')->comment('1=Basement, 2=Ground')->nullable();

            // 1 = Active, 2 = Inactive
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Active, 2=Inactive')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_slots');
    }
};
