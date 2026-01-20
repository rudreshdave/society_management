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
            $table->unsignedBigInteger('flat_id');

            $table->string('alternate_mobile', 15)->nullable();

            $table->date('move_in_date')->nullable();

            $table->string('id_proof_type')->nullable();
            $table->string('id_proof_number')->nullable();
            $table->string('id_proof_document')->nullable(); // file path

            $table->string('emergency_contact', 15)->nullable();

            $table->text('vehicle_info')->nullable(); // JSON or text for multiple vehicles
            $table->string('parking_slot')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // 🔗 Foreign Keys
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('flat_id')
                ->references('id')->on('flats')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
