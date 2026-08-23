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
        // 1. Orders table: delivery_type, payment_type, down_payment, remaining_payment
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('delivery_type', ['delivery', 'pickup'])->default('delivery')->after('customer_phone');
            $table->enum('payment_type', ['full', 'down_payment'])->default('full')->after('status');
            $table->decimal('down_payment', 12, 2)->default(0)->after('payment_type');
            $table->decimal('remaining_payment', 12, 2)->default(0)->after('down_payment');
        });

        // 2. Service Orders table: payment_method
        Schema::table('service_orders', function (Blueprint $table) {
            $table->string('payment_method', 50)->nullable()->after('payment_status');
        });

        // 3. Sell Submissions table: payment_method
        Schema::table('sell_submissions', function (Blueprint $table) {
            $table->string('payment_method', 50)->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_type', 'payment_type', 'down_payment', 'remaining_payment']);
        });

        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method']);
        });

        Schema::table('sell_submissions', function (Blueprint $table) {
            $table->dropColumn(['payment_method']);
        });
    }
};
