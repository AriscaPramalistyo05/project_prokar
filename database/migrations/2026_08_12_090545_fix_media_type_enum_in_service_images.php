<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah ENUM media_type dari ('photo','video') menjadi ('image','video')
     * agar konsisten dengan kode PHP yang menggunakan nilai 'image'.
     * Data lama dengan nilai 'photo' dikonversi ke 'image'.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // 1. Sementara ubah ke VARCHAR agar bisa update data
            DB::statement("ALTER TABLE service_images MODIFY media_type VARCHAR(10) NOT NULL DEFAULT 'image'");

            // 2. Ubah nilai lama 'photo' -> 'image'
            DB::table('service_images')->where('media_type', 'photo')->update(['media_type' => 'image']);

            // 3. Kembalikan ke ENUM dengan nilai yang benar
            DB::statement("ALTER TABLE service_images MODIFY media_type ENUM('image','video') NOT NULL DEFAULT 'image'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE service_images MODIFY media_type VARCHAR(10) NOT NULL DEFAULT 'photo'");
            DB::table('service_images')->where('media_type', 'image')->update(['media_type' => 'photo']);
            DB::statement("ALTER TABLE service_images MODIFY media_type ENUM('photo','video') NOT NULL DEFAULT 'photo'");
        }
    }
};
