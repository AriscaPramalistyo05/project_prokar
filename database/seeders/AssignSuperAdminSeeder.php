<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AssignSuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::find(3);
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        
        if ($user && !$user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
            echo "Role super_admin assigned to user: {$user->email}\n";
        } else {
            echo "User already has super_admin role or user not found.\n";
        }
    }
}