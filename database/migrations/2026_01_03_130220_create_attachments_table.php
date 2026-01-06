<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            // Polymorphic relation
            $table->string('module_type');     // App\Models\Society
            $table->unsignedBigInteger('module_id');

            // File info
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();

            // Ordering (for swapping)
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->index(['module_type', 'module_id']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
