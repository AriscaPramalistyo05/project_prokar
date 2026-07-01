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

        // Super Admin default (jika belum ada)
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@prokar.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('AdminProkar2024!'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Teknisi default (jika belum ada)
        $teknisi = User::updateOrCreate(
            ['email' => 'teknisi@prokar.id'],
            [
                'name' => 'Teknisi Prokar',
                'password' => Hash::make('Teknisi2024!'),
                'email_verified_at' => now(),
            ]
        );
        $teknisi->assignRole('teknisi');

        // User existing Tyo_ariesca — assign super_admin role
        $tyo = User::where('email', 'listyoblaze.uzu@gmail.com')->first();
        if ($tyo && !$tyo->hasRole('super_admin')) {
            $tyo->assignRole('super_admin');
            echo "Role super_admin assigned to: {$tyo->email}\n";
        }

        echo "========================================\n";
        echo "✅ Akun untuk testing berhasil dibuat:\n";
        echo "   Email: admin@prokar.id\n";
        echo "   Password: AdminProkar2024!\n";
        echo "   Role: super_admin\n\n";
        echo "   Email: teknisi@prokar.id\n";
        echo "   Password: Teknisi2024!\n";
        echo "   Role: teknisi\n";
        echo "========================================\n";
    }
}