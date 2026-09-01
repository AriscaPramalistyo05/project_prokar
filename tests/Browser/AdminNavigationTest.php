<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminNavigationTest extends DuskTestCase
{
    public function test_admin_dashboard_navigation_via_wire_navigate(): void
    {
        $admin = User::where('email', 'admin@prokar.id')->first();
        if (!$admin) {
            $admin = User::factory()->create([
                'email' => 'admin@prokar.id',
                'name' => 'Super Admin',
                'email_verified_at' => now(),
            ]);
            $admin->assignRole('super_admin');
        } else {
            if (!$admin->hasRole('super_admin')) {
                $admin->assignRole('super_admin');
            }
        }

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/dashboard')
                ->waitForText('Selamat Datang', 10)
                ->assertSee('Selamat Datang')
                ->pause(300);

            // Navigasi ke menu admin via sidebar
            if ($browser->element('a[href*="/admin/produk"], a[href*="/admin/order"], a[href*="/admin/servis"]')) {
                $browser->click('a[href*="/admin/produk"], a[href*="/admin/order"], a[href*="/admin/servis"]')
                    ->pause(500);
            }
        });
    }
}
