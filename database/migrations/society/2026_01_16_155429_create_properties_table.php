<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('wing_no', 100);
            $table->string('floor_no', 100);
            $table->string('flat_no', 100);
            $table->string('bunglow_no', 100);
            $table->tinyInteger('residency_type')->default(1)->comment('1=Apartment/Flats, 2=Bungalow, 3=Row House');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wings');
    }
};
