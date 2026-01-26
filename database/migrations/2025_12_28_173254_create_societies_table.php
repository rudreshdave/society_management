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
        Schema::create('societies', function (Blueprint $table) {
            $table->id();

            $table->string('society_name');
            $table->string('registration_no')->unique();

            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();

            $table->foreignId('city_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('state_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('pincode', 10);

            $table->string('contact_email');
            $table->string('contact_mobile', 15);

            $table->unsignedInteger('total_wings')->default(0);
            $table->unsignedInteger('total_flats')->default(0);

            // 1 = Active, 2 = Inactive
            $table->tinyInteger('status')->default(1)
                ->comment('1 = Active, 2 = Inactive');

            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            $table->string('database_name')->nullable()->default(null);
            $table->string('database_username')->nullable()->default(null);
            $table->string('database_password')->nullable()->default(null);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // ✅ Soft Deletes column
            $table->softDeletes(); // adds deleted_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('societies');
    }
};
