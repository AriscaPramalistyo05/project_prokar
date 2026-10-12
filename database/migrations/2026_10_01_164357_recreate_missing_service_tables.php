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
        // 1. service_orders table
        if (!Schema::hasTable('service_orders')) {
            Schema::create('service_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('service_code', 50)->unique();
                $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('customer_name', 100);
                $table->string('customer_email', 150)->nullable();
                $table->string('customer_phone', 20);
                $table->enum('service_type', ['home_visit', 'drop_off'])->default('drop_off');
                $table->string('province_id', 2)->nullable();
                $table->string('regency_id', 4)->nullable();
                $table->string('district_id', 7)->nullable();
                $table->string('village_id', 10)->nullable();
                $table->text('address_detail')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
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
                $table->string('payment_method', 50)->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. service_images table
        if (!Schema::hasTable('service_images')) {
            Schema::create('service_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
                $table->string('path', 255);
                $table->enum('type', ['before', 'after', 'complaint'])->default('complaint');
                $table->enum('media_type', ['image', 'video'])->default('image');
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // 3. service_status_logs table
        if (!Schema::hasTable('service_status_logs')) {
            Schema::create('service_status_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
                $table->string('status', 50);
                $table->text('note')->nullable();
                $table->foreignId('changed_by')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            });
        }

        // 4. service_fees table
        if (!Schema::hasTable('service_fees')) {
            Schema::create('service_fees', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
                $table->string('fee_name');
                $table->decimal('amount', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_fees');
        Schema::dropIfExists('service_status_logs');
        Schema::dropIfExists('service_images');
        Schema::dropIfExists('service_orders');
    }
};
