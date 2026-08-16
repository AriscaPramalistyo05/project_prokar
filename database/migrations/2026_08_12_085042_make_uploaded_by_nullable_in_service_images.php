<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat kolom uploaded_by menjadi nullable agar guest (non-login)
     * tetap bisa mengajukan servis + upload foto keluhan.
     * Juga menambahkan kolom media_type jika belum ada.
     */
    public function up(): void
    {
        Schema::table('service_images', function (Blueprint $table) {
            // Tambahkan kolom media_type jika belum ada
            if (!Schema::hasColumn('service_images', 'media_type')) {
                $table->enum('media_type', ['image', 'video'])->default('image')->after('type');
            }

            // Drop foreign key constraint lama sebelum mengubah kolom
            $table->dropForeign(['uploaded_by']);

            // Ubah uploaded_by menjadi nullable
            $table->unsignedBigInteger('uploaded_by')->nullable()->change();

            // Re-add foreign key dengan nullable support
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_images', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by']);
            $table->unsignedBigInteger('uploaded_by')->nullable(false)->change();
            $table->foreign('uploaded_by')->references('id')->on('users')->cascadeOnDelete();

            if (Schema::hasColumn('service_images', 'media_type')) {
                $table->dropColumn('media_type');
            }
        });
    }
};
