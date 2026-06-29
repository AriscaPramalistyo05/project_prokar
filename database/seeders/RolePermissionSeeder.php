<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Permissions ──────────────────────────────────────────
        $permissions = [
            // Produk
            'view_products', 'create_product', 'edit_product', 'delete_product',
            // Servis
            'view_services', 'create_service', 'edit_service', 'update_service_status',
            'input_service_cost', 'delete_service',
            // Jual (barang masuk)
            'view_sell_submissions', 'review_sell_submission',
            'accept_sell_submission', 'reject_sell_submission',
            // Order
            'view_orders', 'update_order_status',
            // User & Role
            'view_users', 'create_user', 'edit_user', 'delete_user', 'manage_roles',
            // Laporan
            'view_reports', 'export_reports',
            // Setting
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ── Role: super_admin ───────────────────────────────────
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions($permissions); // semua permission

        // ── Role: teknisi ──────────────────────────────────────
        $teknisi = Role::firstOrCreate(['name' => 'teknisi']);
        $teknisi->syncPermissions([
            'view_services', 'create_service', 'edit_service', 'update_service_status',
            'input_service_cost',
        ]);
    }
}
