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
        $tables = ['users', 'service_orders', 'sell_submissions', 'orders'];
        
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // Drop existing old address columns if they exist
                if ($tableName === 'service_orders') {
                    $table->dropColumn(['customer_address', 'customer_city']);
                }
                if ($tableName === 'sell_submissions') {
                    $table->dropColumn(['customer_city']);
                }
                if ($tableName === 'orders') {
                    $table->dropColumn(['customer_address', 'customer_city']);
                }

                $table->string('province_id', 2)->nullable();
                $table->string('regency_id', 4)->nullable();
                $table->string('district_id', 7)->nullable();
                $table->string('village_id', 10)->nullable();
                $table->text('address_detail')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
