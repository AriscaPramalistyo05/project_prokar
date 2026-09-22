<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role sudah ada
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $teknisiRole = Role::firstOrCreate(['name' => 'teknisi', 'guard_name' => 'web']);

        $adminPassword = env('ADMIN_DEFAULT_PASSWORD', 'AdminProkar2024!');
        $teknisiPassword = env('TEKNISI_DEFAULT_PASSWORD', 'Teknisi2024!');

        // Super Admin default (jika belum ada)
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@prokar.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Teknisi default (jika belum ada)
        $teknisi = User::updateOrCreate(
            ['email' => 'teknisi@prokar.id'],
            [
                'name' => 'Teknisi Prokar',
                'password' => Hash::make($teknisiPassword),
                'email_verified_at' => now(),
            ]
        );
        $teknisi->assignRole('teknisi');

        echo "========================================\n";
        echo "✅ Akun untuk testing/admin siap:\n";
        echo "   Email: admin@prokar.id\n";
        echo "   Role: super_admin\n\n";
        echo "   Email: teknisi@prokar.id\n";
        echo "   Role: teknisi\n";
        echo "========================================\n";
    }
}
