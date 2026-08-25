<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    protected function setupRoles(): void
    {
        $this->seed(RolePermissionSeeder::class);
    }

    protected function actingAsSuperAdmin(): User
    {
        $this->setupRoles();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $this->actingAs($user);
        return $user;
    }

    protected function actingAsTeknisi(): User
    {
        $this->setupRoles();
        $user = User::factory()->create();
        $user->assignRole('teknisi');
        $this->actingAs($user);
        return $user;
    }

    protected function actingAsCustomer(): User
    {
        $this->setupRoles();
        $user = User::factory()->create();
        $user->assignRole('customer');
        $this->actingAs($user);
        return $user;
    }
}

