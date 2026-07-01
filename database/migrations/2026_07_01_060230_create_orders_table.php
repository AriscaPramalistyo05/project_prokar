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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name', 100);
            $table->string('customer_email', 150);
            $table->string('customer_phone', 20);
            $table->text('customer_address');
            $table->string('customer_city', 100);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->string('payment_method', 50)->nullable();
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->string('midtrans_order_id', 100)->nullable();
            $table->string('midtrans_token', 255)->nullable();
            $table->json('midtrans_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
