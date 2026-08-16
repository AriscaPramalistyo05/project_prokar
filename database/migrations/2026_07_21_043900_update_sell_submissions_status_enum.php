<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE sell_submissions MODIFY COLUMN status ENUM('pending', 'reviewing', 'negotiating', 'accepted', 'rejected', 'paid', 'in_repair', 'ready_for_sale') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE sell_submissions MODIFY COLUMN status ENUM('pending', 'reviewing', 'negotiating', 'accepted', 'rejected', 'paid') DEFAULT 'pending'");
    }
};
