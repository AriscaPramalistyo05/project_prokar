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
        Schema::table('sell_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('sell_submissions', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('sell_submissions', 'customer_email')) {
                $table->string('customer_email', 150)->nullable()->after('customer_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sell_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('sell_submissions', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('sell_submissions', 'customer_email')) {
                $table->dropColumn('customer_email');
            }
        });
    }
};
