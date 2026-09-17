<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LogViewerSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'view_system_logs', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'teknisi']);
        Role::firstOrCreate(['name' => 'customer']);
    }

    public function test_guest_cannot_access_log_viewer(): void
    {
        $response = $this->get('/admin/logs');
        $response->assertRedirect(route('login'));
    }

    public function test_non_super_admin_cannot_access_log_viewer(): void
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        $response = $this->actingAs($user)->get('/admin/logs');
        $response->assertForbidden();
    }

    public function test_super_admin_can_access_log_viewer(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');

        $response = $this->actingAs($admin)->get('/admin/logs');
        $response->assertStatus(200);
    }

    public function test_staff_with_view_system_logs_permission_can_access_log_viewer(): void
    {
        $role = Role::firstOrCreate(['name' => 'staff_audit', 'guard_name' => 'web']);
        $role->givePermissionTo('view_system_logs');

        $staff = User::factory()->create();
        $staff->assignRole('staff_audit');

        $response = $this->actingAs($staff)->get('/admin/logs');
        $response->assertStatus(200);
    }
}
