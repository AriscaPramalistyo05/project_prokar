<?php

namespace Tests\Feature;

use App\Models\ServiceOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarrantyPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_completed_service_generates_downloadable_pdf_warranty_card(): void
    {
        $service = ServiceOrder::factory()->create([
            'status' => 'completed',
            'warranty_until' => now()->addDays(30),
        ]);

        $response = $this->get(route('servis.garansi.download', ['code' => $service->service_code]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_warranty_pdf_cannot_be_downloaded_before_completed_status(): void
    {
        $service = ServiceOrder::factory()->create([
            'status' => 'in_progress',
        ]);

        $response = $this->get(route('servis.garansi.download', ['code' => $service->service_code]));

        $response->assertForbidden();
    }
}
