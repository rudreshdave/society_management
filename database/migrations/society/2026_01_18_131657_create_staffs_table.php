<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('designation')->nullable();
            $table->decimal('salary', 10, 2)->nullable();

            $table->enum('role', ['Guard', 'Cleaner', 'Electrician']);

            $table->enum('shift', ['Morning', 'Night', 'Rotational'])
                ->default('Morning');

            $table->date('joining_date')->nullable();

            $table->string('id_proof_type')->nullable();
            $table->string('id_proof_document')->nullable(); // file path

            $table->text('address')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // 🔗 Foreign Key
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
