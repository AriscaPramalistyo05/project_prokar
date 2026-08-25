<?php

namespace Tests\Unit;

use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SettingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_encrypted_key_stored_ciphertext(): void
    {
        $settingService = app(SettingService::class);
        $rawSecret = 'MySuperSecretToken2026';

        // Simpan sensitive key
        $settingService->set('midtrans_server_key', $rawSecret);
        $settingService->set('google_client_secret', $rawSecret);

        // Cek langsung nilai mentah di tabel database
        $dbRecord = Setting::where('key', 'midtrans_server_key')->first();
        $this->assertNotNull($dbRecord);
        $this->assertNotEquals($rawSecret, $dbRecord->value);

        $googleDbRecord = Setting::where('key', 'google_client_secret')->first();
        $this->assertNotNull($googleDbRecord);
        $this->assertNotEquals($rawSecret, $googleDbRecord->value);

        // Cek dekripsi saat diminta
        $this->assertEquals($rawSecret, $settingService->get('midtrans_server_key', true));
        $this->assertEquals($rawSecret, $settingService->get('google_client_secret', true));
    }

    public function test_cache_remember_and_forget_on_set(): void
    {
        $settingService = app(SettingService::class);

        // 1. Simpan nilai awal
        $settingService->set('shop_name', 'Prokar Elektronik Initial');
        $this->assertEquals('Prokar Elektronik Initial', $settingService->get('shop_name'));

        // Cek cache tersimpan
        $this->assertEquals('Prokar Elektronik Initial', Cache::get('setting_shop_name'));

        // 2. Ubah nilai -> Cache otomatis dibersihkan dan diupdate
        $settingService->set('shop_name', 'Prokar Elektronik Updated');
        $this->assertEquals('Prokar Elektronik Updated', $settingService->get('shop_name'));
        $this->assertEquals('Prokar Elektronik Updated', Cache::get('setting_shop_name'));
    }
}
