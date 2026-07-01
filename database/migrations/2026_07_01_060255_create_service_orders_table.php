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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('service_code', 50)->unique();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name', 100);
            $table->string('customer_email', 150)->nullable();
            $table->string('customer_phone', 20);
            $table->enum('service_type', ['home_visit', 'drop_off']);
            $table->text('customer_address')->nullable();
            $table->string('customer_city', 100)->nullable();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('device_brand', 100);
            $table->string('device_model', 100)->nullable();
            $table->text('complaint');
            $table->text('diagnosis')->nullable();
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->decimal('final_cost', 12, 2)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'diagnosing', 'waiting_approval', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('customer_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->date('warranty_until')->nullable();
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
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
        Schema::dropIfExists('service_orders');
    }
};
