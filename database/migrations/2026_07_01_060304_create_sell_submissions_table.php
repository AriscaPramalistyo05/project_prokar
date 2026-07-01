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
        Schema::create('sell_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('submission_code', 50)->unique();
            $table->string('customer_name', 100);
            $table->string('customer_phone', 20);
            $table->string('customer_whatsapp', 20)->nullable();
            $table->string('customer_city', 100);
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('device_brand', 100);
            $table->string('device_model', 100)->nullable();
            $table->enum('condition', ['good', 'fair', 'needs_repair']);
            $table->text('description')->nullable();
            $table->decimal('offered_price', 12, 2)->nullable();
            $table->decimal('agreed_price', 12, 2)->nullable();
            $table->enum('status', ['pending', 'reviewing', 'negotiating', 'accepted', 'rejected', 'paid'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('physical_check_at')->nullable();
            $table->timestamp('payment_at')->nullable();
            $table->foreignId('converted_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_submissions');
    }
};
