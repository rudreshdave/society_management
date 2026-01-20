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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();

            $table->string('visitor_name');
            $table->unsignedBigInteger('flat_id');

            $table->dateTime('check_in_time')->nullable();
            $table->dateTime('check_out_time')->nullable();

            // 1 = Guest, 2 = Delivery, 3 = Cab
            $table->unsignedTinyInteger('type')
                ->comment('1=Guest, 2=Delivery, 3=Cab');

            // 1 = PreApprove, 2 = Approve, 3 = Rejected
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->comment('1=PreApprove, 2=Approve, 3=Rejected');

            $table->unsignedBigInteger('created_by'); // guard / admin / resident
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys (recommended)
            $table->foreign('flat_id')
                ->references('id')->on('flats')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
