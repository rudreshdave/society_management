<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();

            $table->enum('resident_type', ['Owner', 'Renter']);

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('property_id');

            $table->string('alternate_mobile', 15)->nullable();

            $table->date('move_in_date')->nullable();

            $table->string('emergency_contact', 15)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // 🔗 Foreign Keys
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('property_id')
                ->references('id')->on('properties')
                ->onDelete('cascade');

            $table->foreign('parking_slot_id')
                ->references('id')->on('parking_slots')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
