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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->string('brand', 100);
            $table->string('model', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('condition_notes')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('promo_price', 12, 2)->nullable();
            $table->unsignedTinyInteger('stock')->default(1);
            $table->enum('status', ['available', 'reserved', 'sold', 'unavailable'])->default('available');
            $table->boolean('is_promo')->default(false);
            $table->string('meta_title', 200)->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
