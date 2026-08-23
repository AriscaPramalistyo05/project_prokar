<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

#[Layout('layouts.admin')]
class RolePermissionIndex extends Component
{
    use Toast;

    public string $newRoleName = '';
    public bool $showNewRoleModal = false;

    // Modal Hapus Role
    public bool $showDeleteRoleModal = false;
    public ?int $deleteRoleId = null;
    public string $deleteRoleName = '';

    public function togglePermission(int $roleId, string $permissionName): void
    {
        $role = Role::findOrFail($roleId);

        // Jangan izinkan mengubah role super_admin (selalu punya semua hak)
        if ($role->name === 'super_admin') {
            $this->warning('Role Terkunci', 'Hak akses Super Admin selalu penuh dan tidak dapat dikurangi demi keamanan sistem.');
            return;
        }

        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
            $actionText = 'dicabut dari';
        } else {
            $role->givePermissionTo($permissionName);
            $actionText = 'diberikan kepada';
        }

        // Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permLabel = $this->getPermissionLabel($permissionName);
        $roleLabel = ucwords(str_replace('_', ' ', $role->name));
        $this->success('Hak Akses Diperbarui', "Izin '{$permLabel}' berhasil {$actionText} role '{$roleLabel}'.");
    }

    public function createRole(): void
    {
        $this->validate([
            'newRoleName' => 'required|string|max:50|unique:roles,name',
        ], [
            'newRoleName.required' => 'Nama role wajib diisi.',
            'newRoleName.unique' => 'Nama role ini sudah ada.',
        ]);

        $roleSlug = strtolower(str_replace(' ', '_', trim($this->newRoleName)));

        Role::create([
            'name' => $roleSlug,
            'guard_name' => 'web',
        ]);

        $this->newRoleName = '';
        $this->showNewRoleModal = false;
        $this->success('Role Dibuat', "Role baru '{$roleSlug}' berhasil ditambahkan.");
    }

    public function confirmDeleteRole(int $roleId): void
    {
        $role = Role::findOrFail($roleId);

        if (in_array($role->name, ['super_admin', 'teknisi'])) {
            $this->error('Gagal', "Role bawaan sistem '{$role->name}' tidak boleh dihapus.");
            return;
        }

        $this->deleteRoleId = $role->id;
        $this->deleteRoleName = $role->name;
        $this->showDeleteRoleModal = true;
    }

    public function deleteRole(): void
    {
        if (!$this->deleteRoleId) {
            return;
        }

        $role = Role::findOrFail($this->deleteRoleId);

        if (in_array($role->name, ['super_admin', 'teknisi'])) {
            $this->error('Gagal', "Role bawaan sistem '{$role->name}' tidak boleh dihapus.");
            $this->showDeleteRoleModal = false;
            return;
        }

        $roleName = $role->name;
        $role->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->showDeleteRoleModal = false;
        $this->deleteRoleId = null;
        $this->deleteRoleName = '';
        $this->success('Role Dihapus', "Role '{$roleName}' berhasil dihapus.");
    }

    public function getPermissionLabel(string $name): string
    {
        $labels = [
            'view_products' => 'Lihat Produk',
            'create_product' => 'Tambah Produk Baru',
            'edit_product' => 'Edit Produk',
            'delete_product' => 'Hapus Produk',

            'view_services' => 'Lihat Antrean Servis',
            'create_service' => 'Tambah Servis Baru',
            'edit_service' => 'Edit Data Servis',
            'update_service_status' => 'Ubah Status Pengerjaan Servis',
            'input_service_cost' => 'Input Biaya & Sparepart Servis',
            'delete_service' => 'Hapus Servis',

            'view_sell_submissions' => 'Lihat Barang Masuk / Jual Bekas',
            'review_sell_submission' => 'Review & Cek Fisik Barang',
            'accept_sell_submission' => 'Setujui & Buat Penawaran Harga',
            'reject_sell_submission' => 'Tolak Pengajuan Barang',

            'view_orders' => 'Lihat Daftar Order Penjualan',
            'update_order_status' => 'Ubah Status Pesanan & Pembayaran',

            'view_users' => 'Lihat Daftar Pengguna',
            'create_user' => 'Tambah Pengguna Baru',
            'edit_user' => 'Edit Pengguna & Akun',
            'delete_user' => 'Hapus Pengguna',
            'manage_roles' => 'Kelola Role & Hak Akses (Matrix)',

            'view_reports' => 'Lihat Laporan Transaksi & Statistik',
            'export_reports' => 'Export Laporan Excel & PDF',

            'manage_settings' => 'Kelola Pengaturan Sistem (Setting)',
        ];

        return $labels[$name] ?? ucwords(str_replace('_', ' ', $name));
    }

    public function render()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();

        $permissionGroups = [
            'Produk (Katalog & Stok)' => [
                'icon' => 'o-cube',
                'permissions' => ['view_products', 'create_product', 'edit_product', 'delete_product'],
            ],
            'Servis Elektronik' => [
                'icon' => 'o-wrench-screwdriver',
                'permissions' => ['view_services', 'create_service', 'edit_service', 'update_service_status', 'input_service_cost', 'delete_service'],
            ],
            'Jual Bekas / Barang Masuk' => [
                'icon' => 'o-arrow-down-tray',
                'permissions' => ['view_sell_submissions', 'review_sell_submission', 'accept_sell_submission', 'reject_sell_submission'],
            ],
            'Order Penjualan' => [
                'icon' => 'o-shopping-bag',
                'permissions' => ['view_orders', 'update_order_status'],
            ],
            'Pengguna & Role' => [
                'icon' => 'o-users',
                'permissions' => ['view_users', 'create_user', 'edit_user', 'delete_user', 'manage_roles'],
            ],
            'Laporan & Analitik' => [
                'icon' => 'o-chart-bar',
                'permissions' => ['view_reports', 'export_reports'],
            ],
            'Pengaturan Sistem' => [
                'icon' => 'o-cog-6-tooth',
                'permissions' => ['manage_settings'],
            ],
        ];

        return view('livewire.admin.role-permission-index', [
            'roles' => $roles,
            'permissionGroups' => $permissionGroups,
        ]);
    }
}
