<?php

namespace Tests\Feature;

use App\Livewire\Admin\SettingIndex;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupRoles();
    }

    public function test_setting_helper_retrieves_value_from_cache_and_db(): void
    {
        $settingService = app(SettingService::class);
        $settingService->set('shop_name', 'Prokar Elektronik Official');

        $this->assertEquals('Prokar Elektronik Official', setting('shop_name'));
        $this->assertEquals('Prokar Elektronik Official', Cache::get('setting_shop_name'));
    }

    public function test_sensitive_settings_stored_encrypted_in_database(): void
    {
        $settingService = app(SettingService::class);
        $secretKey = 'SB-Mid-server-super-secret-key-123';
        $settingService->set('midtrans_server_key', $secretKey);
        $settingService->set('google_client_secret', 'GOCSPX-secret-token-123');

        $rawRecord = Setting::where('key', 'midtrans_server_key')->first();
        $this->assertNotNull($rawRecord);
        $this->assertNotEquals($secretKey, $rawRecord->value);

        $rawGoogleRecord = Setting::where('key', 'google_client_secret')->first();
        $this->assertNotNull($rawGoogleRecord);
        $this->assertNotEquals('GOCSPX-secret-token-123', $rawGoogleRecord->value);
    }

    public function test_sensitive_settings_decrypted_correctly_when_requested(): void
    {
        $settingService = app(SettingService::class);
        $secretKey = 'SB-Mid-server-super-secret-key-123';
        $settingService->set('midtrans_server_key', $secretKey);
        $settingService->set('google_client_secret', 'GOCSPX-secret-token-123');

        $this->assertEquals($secretKey, setting('midtrans_server_key', true));
        $this->assertEquals('GOCSPX-secret-token-123', setting('google_client_secret', true));
    }

    public function test_updating_settings_clears_cached_settings(): void
    {
        $settingService = app(SettingService::class);
        $settingService->set('shop_tagline', 'Solusi Elektronik Terpercaya');
        $this->assertEquals('Solusi Elektronik Terpercaya', setting('shop_tagline'));

        // Update value
        $settingService->set('shop_tagline', 'Pusat Elektronik Murah & Bergaransi');
        $this->assertEquals('Pusat Elektronik Murah & Bergaransi', setting('shop_tagline'));
    }

    public function test_admin_can_update_shop_identity_and_contact_info(): void
    {
        $admin = $this->actingAsSuperAdmin();

        Livewire::actingAs($admin)
            ->test(SettingIndex::class)
            ->set('shop_name', 'Prokar Service & Thrift')
            ->set('shop_whatsapp', '081234567890')
            ->set('shop_email', 'contact@prokar.id')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('Prokar Service & Thrift', setting('shop_name'));
        $this->assertEquals('081234567890', setting('shop_whatsapp'));
        $this->assertEquals('contact@prokar.id', setting('shop_email'));
    }

    public function test_admin_can_switch_hero_card_mode_between_3_card_and_6_card(): void
    {
        $admin = $this->actingAsSuperAdmin();

        Livewire::actingAs($admin)
            ->test(SettingIndex::class)
            ->set('hero_card_mode', '3_card')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('3_card', setting('hero_card_mode'));

        Livewire::actingAs($admin)
            ->test(SettingIndex::class)
            ->set('hero_card_mode', '6_card')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('6_card', setting('hero_card_mode'));
    }

    public function test_admin_can_customize_hero_headline_segments_and_colors(): void
    {
        $admin = $this->actingAsSuperAdmin();

        Livewire::actingAs($admin)
            ->test(SettingIndex::class)
            ->set('hero_headline_1', 'PUSAT BELANJA')
            ->set('hero_headline_color_1', 'kuning')
            ->set('hero_headline_2', 'ELEKTRONIK JEPARA')
            ->set('hero_headline_color_2', 'hitam')
            ->set('hero_headline_3', 'BERGARANSI')
            ->set('hero_headline_color_3', 'biru')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('PUSAT BELANJA', setting('hero_headline_1'));
        $this->assertEquals('kuning', setting('hero_headline_color_1'));
        $this->assertEquals('ELEKTRONIK JEPARA', setting('hero_headline_2'));
        $this->assertEquals('hitam', setting('hero_headline_color_2'));
        $this->assertEquals('BERGARANSI', setting('hero_headline_3'));
        $this->assertEquals('biru', setting('hero_headline_color_3'));
    }

    public function test_teknisi_and_guest_forbidden_from_admin_settings(): void
    {
        $this->get(route('admin.settings'))
            ->assertRedirect(route('login'));

        $teknisi = $this->actingAsTeknisi();
        $this->actingAs($teknisi)
            ->get(route('admin.settings'))
            ->assertForbidden();
    }

    public function test_admin_can_upload_logo_and_favicon_and_update_settings(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = $this->actingAsSuperAdmin();
        $logoFile = \Illuminate\Http\UploadedFile::fake()->image('custom_logo.png', 300, 100);
        $faviconFile = \Illuminate\Http\UploadedFile::fake()->image('custom_favicon.png', 32, 32);

        Livewire::actingAs($admin)
            ->test(SettingIndex::class)
            ->set('logo_file', $logoFile)
            ->set('favicon_file', $faviconFile)
            ->call('save')
            ->assertHasNoErrors();

        $savedLogo = setting('shop_logo');
        $savedFavicon = setting('shop_favicon');

        $this->assertNotNull($savedLogo);
        $this->assertNotNull($savedFavicon);

        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($savedLogo);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($savedFavicon);
    }
}
