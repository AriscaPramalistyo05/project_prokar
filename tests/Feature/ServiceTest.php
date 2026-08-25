<?php

namespace Tests\Feature;

use App\Livewire\Admin\ServiceDetail;
use App\Livewire\Frontend\ServiceForm;
use App\Livewire\Frontend\TrackService;
use App\Models\Category;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupRoles();
    }

    public function test_customer_can_submit_service_request_drop_off(): void
    {
        $category = Category::factory()->create(['name' => 'Kulkas']);

        Livewire::test(ServiceForm::class)
            ->set('nama', 'Budi Hartono')
            ->set('email', 'budi@example.com')
            ->set('whatsapp', '081234567890')
            ->set('serviceType', 'kirim') // drop_off
            ->set('kategori', $category->id)
            ->set('merek', 'Samsung Digital Inverter')
            ->set('deskripsi', 'Kulkas tidak dingin sama sekali sejak 2 hari lalu.')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('service_orders', [
            'customer_name' => 'Budi Hartono',
            'customer_email' => 'budi@example.com',
            'service_type' => 'drop_off',
            'status' => 'pending',
        ]);
    }

    public function test_customer_can_submit_service_request_home_visit(): void
    {
        $category = Category::factory()->create(['name' => 'Mesin Cuci']);

        Livewire::test(ServiceForm::class)
            ->set('nama', 'Siti Rahma')
            ->set('email', 'siti@example.com')
            ->set('whatsapp', '081234567891')
            ->set('serviceType', 'datang') // home_visit
            ->set('province_id', '33')
            ->set('regency_id', '3320')
            ->set('district_id', '3320010')
            ->set('village_id', '3320010001')
            ->set('address_detail', 'Jl. Tahunan Raya No. 45 RT 02 RW 03')
            ->set('kategori', $category->id)
            ->set('merek', 'LG TurboWash')
            ->set('deskripsi', 'Mesin cuci bergetar kencang dan bocor air.')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('service_orders', [
            'customer_name' => 'Siti Rahma',
            'service_type' => 'home_visit',
            'address_detail' => 'Jl. Tahunan Raya No. 45 RT 02 RW 03',
        ]);
    }

    public function test_home_visit_submission_requires_address_detail(): void
    {
        $category = Category::factory()->create();

        Livewire::test(ServiceForm::class)
            ->set('nama', 'Test User')
            ->set('email', 'test@example.com')
            ->set('whatsapp', '081234567890')
            ->set('serviceType', 'datang')
            ->set('address_detail', '')
            ->set('kategori', $category->id)
            ->set('merek', 'Sharp')
            ->set('deskripsi', 'Kerusakan pada kompresor kulkas.')
            ->call('submit')
            ->assertHasErrors(['address_detail']);
    }

    public function test_service_code_auto_generated_in_format_srv_yyyymmdd_xxxx(): void
    {
        $category = Category::factory()->create();

        $service1 = ServiceOrder::factory()->create(['category_id' => $category->id]);
        $service2 = ServiceOrder::factory()->create(['category_id' => $category->id]);

        $this->assertMatchesRegularExpression('/^SRV-\d{8}-\d{4}$/', $service1->service_code);
        $this->assertMatchesRegularExpression('/^SRV-\d{8}-\d{4}$/', $service2->service_code);
    }

    public function test_customer_can_track_service_status_using_service_code(): void
    {
        $service = ServiceOrder::factory()->create([
            'device_brand' => 'LG Inverter 2 Pintu',
            'complaint' => 'Kompresor mati total',
            'status' => 'in_progress',
        ]);

        Livewire::test(TrackService::class, ['code' => $service->service_code])
            ->assertSee($service->service_code);
    }

    public function test_customer_can_approve_cost_estimate(): void
    {
        $service = ServiceOrder::factory()->create([
            'status' => 'waiting_approval',
            'estimated_cost' => 350000,
        ]);

        Livewire::test(TrackService::class, ['code' => $service->service_code])
            ->call('approveCost');

        $this->assertEquals('in_progress', $service->fresh()->status);
    }

    public function test_customer_cannot_approve_estimate_for_cancelled_service(): void
    {
        $service = ServiceOrder::factory()->create([
            'status' => 'cancelled',
        ]);

        Livewire::test(TrackService::class, ['code' => $service->service_code])
            ->call('approveCost');

        $this->assertEquals('cancelled', $service->fresh()->status);
    }

    public function test_admin_can_assign_service_to_teknisi(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $teknisi = User::factory()->create();
        $teknisi->assignRole('teknisi');

        $service = ServiceOrder::factory()->create(['status' => 'pending']);

        Livewire::actingAs($admin)
            ->test(ServiceDetail::class, ['serviceOrder' => $service])
            ->set('new_technician_id', $teknisi->id)
            ->call('assignTechnician');

        $this->assertEquals($teknisi->id, $service->fresh()->technician_id);
    }

    public function test_teknisi_can_update_service_status_and_diagnosis_notes(): void
    {
        $teknisi = $this->actingAsTeknisi();
        $service = ServiceOrder::factory()->create([
            'technician_id' => $teknisi->id,
            'status' => 'in_progress',
        ]);

        Livewire::actingAs($teknisi)
            ->test(ServiceDetail::class, ['serviceOrder' => $service])
            ->set('new_diagnosis', 'Kapasitor rusak dan relay terbakar, sudah diganti.')
            ->set('new_estimated_cost', 250000)
            ->call('submitEstimate');

        $this->assertEquals('Kapasitor rusak dan relay terbakar, sudah diganti.', $service->fresh()->diagnosis);
        $this->assertEquals(250000, $service->fresh()->estimated_cost);
    }

    public function test_teknisi_cannot_access_unassigned_service_order(): void
    {
        $teknisi1 = $this->actingAsTeknisi();
        $teknisi2 = User::factory()->create();
        $teknisi2->assignRole('teknisi');

        $service = ServiceOrder::factory()->create([
            'technician_id' => $teknisi2->id,
        ]);

        Livewire::actingAs($teknisi1)
            ->test(ServiceDetail::class, ['serviceOrder' => $service])
            ->assertForbidden();
    }
}
