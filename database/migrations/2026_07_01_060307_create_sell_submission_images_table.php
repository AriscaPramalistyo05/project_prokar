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
        Schema::create('sell_submission_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sell_submission_id')->constrained('sell_submissions')->cascadeOnDelete();
            $table->string('path', 255);
            $table->enum('type', ['photo', 'video']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_submission_images');
    }
};
