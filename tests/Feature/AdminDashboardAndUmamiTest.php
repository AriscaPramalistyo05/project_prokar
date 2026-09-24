<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardAndUmamiTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        $this->adminUser = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->adminUser->assignRole($role);
    }

    public function test_umami_tracking_script_is_present_on_public_home(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->getContent(), 'vendor/umami/script.js') ||
            str_contains($response->getContent(), 'https://cloud.umami.is/script.js'),
            'Umami tracking script should be present on public home'
        );
        $response->assertSee('data-website-id="6150499f-eb3e-406f-b3d1-d9834bb6bfc9"', false);
    }

    public function test_umami_tracking_script_is_present_on_login_guest_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->getContent(), 'vendor/umami/script.js') ||
            str_contains($response->getContent(), 'https://cloud.umami.is/script.js'),
            'Umami tracking script should be present on login guest page'
        );
        $response->assertSee('data-website-id="6150499f-eb3e-406f-b3d1-d9834bb6bfc9"', false);
    }

    public function test_admin_dashboard_renders_with_umami_traffic_card_and_no_emojis(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Umami script in admin layout
        $this->assertTrue(
            str_contains($response->getContent(), 'vendor/umami/script.js') ||
            str_contains($response->getContent(), 'https://cloud.umami.is/script.js'),
            'Umami tracking script should be present in admin layout'
        );
        $response->assertSee('6150499f-eb3e-406f-b3d1-d9834bb6bfc9', false);

        // Native Umami traffic cards & charts content
        $response->assertSee('Trafik Pengunjung Website', false);
        $response->assertSee('Pengunjung Aktif Saat Ini', false);
        $response->assertSee('Tayangan (Views)', false);
        $response->assertSee('Pengunjung Unik', false);
        $response->assertSee('Total Kunjungan', false);
        $response->assertSee('umamiVisitorsChart', false);
        $response->assertSee('Halaman Terpopuler', false);

        // Must NOT contain iframe preview
        $response->assertDontSee('<iframe', false);

        // Must NOT contain 👋 emoji
        $response->assertDontSee('👋', false);

        // Must NOT contain linear gradient in canvas scripts
        $response->assertDontSee('createLinearGradient', false);
    }
}
