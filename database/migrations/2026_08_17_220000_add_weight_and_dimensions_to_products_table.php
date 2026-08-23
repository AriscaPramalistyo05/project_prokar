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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('weight')->default(1000)->after('stock')->comment('Berat timbangan dalam gram');
            $table->unsignedInteger('length')->nullable()->after('weight')->comment('Panjang kemasan dalam cm');
            $table->unsignedInteger('width')->nullable()->after('length')->comment('Lebar kemasan dalam cm');
            $table->unsignedInteger('height')->nullable()->after('width')->comment('Tinggi kemasan dalam cm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['weight', 'length', 'width', 'height']);
        });
    }
};
