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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->string('ticket_no')->unique();

            $table->unsignedBigInteger('residents_id');

            // 1=Plumbing, 2=Electricity, 3=Noise, 4=Other
            $table->unsignedTinyInteger('category')
                ->comment('1=Plumbing, 2=Electricity, 3=Noise, 4=Other');

            $table->text('description');

            // 1=Low, 2=Medium, 3=High
            $table->unsignedTinyInteger('priority')
                ->comment('1=Low, 2=Medium, 3=High');

            // 1=Open, 2=InProgress, 3=Completed, 4=Rejected
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->comment('1=Open, 2=InProgress, 3=Completed, 4=Rejected');

            $table->unsignedBigInteger('assigned_staff')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('residents_id')
                ->references('id')->on('residents')
                ->onDelete('cascade');

            $table->foreign('assigned_staff')
                ->references('id')->on('staffs')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
