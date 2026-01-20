<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wing_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('flat_no', 50);
            $table->integer('floor_no');
            $table->string('flat_type', 50)->comment('1BHK / 2BHK / Duplex');
            $table->integer('sq_ft_area');
            $table->tinyInteger('status')->comment('1=Vacant, 2=Occupied');
            $table->timestamps();
            $table->softDeletes();

            // Optional: prevent duplicate flat numbers in same wing
            $table->unique(['wing_id', 'flat_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flats');
    }
};
