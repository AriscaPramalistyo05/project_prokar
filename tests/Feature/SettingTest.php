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

    public function test_admin_can_update_hero_3card_titles(): void
    {
        $admin = $this->actingAsSuperAdmin();

        Livewire::actingAs($admin)
            ->test(SettingIndex::class)
            ->set('hero_3card_title_1', 'Kulkas 2 Pintu')
            ->set('hero_3card_title_2', 'Smart TV')
            ->set('hero_3card_title_3', 'Mesin Cuci Otomatis')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('Kulkas 2 Pintu', setting('hero_3card_title_1'));
        $this->assertEquals('Smart TV', setting('hero_3card_title_2'));
        $this->assertEquals('Mesin Cuci Otomatis', setting('hero_3card_title_3'));
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

    public function test_admin_can_create_testimonial_via_modal(): void
    {
        $admin = $this->actingAsSuperAdmin();

        Livewire::actingAs($admin)
            ->test(SettingIndex::class)
            ->call('openCreateTestimonialModal')
            ->assertSet('testimonialModal', true)
            ->set('testimonial_name', 'Rizky Pratama')
            ->set('testimonial_role', 'Pembeli LED TV 50 Inch')
            ->set('testimonial_quote', 'Barangnya original, gambar jernih dan bergaransi resmi.')
            ->set('testimonial_rating', 5)
            ->call('saveTestimonial')
            ->assertHasNoErrors()
            ->assertSet('testimonialModal', false);

        $testimonials = json_decode(setting('testimonials') ?? '[]', true);
        $this->assertNotEmpty($testimonials);
        $last = end($testimonials);
        $this->assertEquals('Rizky Pratama', $last['name']);
        $this->assertEquals('Pembeli LED TV 50 Inch', $last['role']);
        $this->assertEquals('Barangnya original, gambar jernih dan bergaransi resmi.', $last['quote']);
        $this->assertEquals(5, $last['rating']);
    }

    public function test_admin_can_update_testimonial_via_modal(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $lw = Livewire::actingAs($admin)->test(SettingIndex::class);
        $lw->call('openEditTestimonialModal', 0)
            ->assertSet('testimonialModal', true)
            ->set('testimonial_name', 'Ahmad Fauzi Edited')
            ->set('testimonial_quote', 'Ulasan diperbarui oleh admin untuk keperluan testimoni.')
            ->call('saveTestimonial')
            ->assertHasNoErrors()
            ->assertSet('testimonialModal', false);

        $testimonials = json_decode(setting('testimonials') ?? '[]', true);
        $this->assertEquals('Ahmad Fauzi Edited', $testimonials[0]['name']);
        $this->assertEquals('Ulasan diperbarui oleh admin untuk keperluan testimoni.', $testimonials[0]['quote']);
    }

    public function test_admin_can_delete_testimonial(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $lw = Livewire::actingAs($admin)->test(SettingIndex::class);
        $initialCount = count($lw->get('testimonials'));

        $lw->call('confirmDeleteTestimonial', 0)
            ->assertSet('deleteTestimonialModal', true)
            ->call('deleteTestimonial')
            ->assertSet('deleteTestimonialModal', false);

        $testimonials = json_decode(setting('testimonials') ?? '[]', true);
        $this->assertCount($initialCount - 1, $testimonials);
    }

    public function test_admin_can_reorder_testimonials(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $lw = Livewire::actingAs($admin)->test(SettingIndex::class);
        $firstItemName = $lw->get('testimonials')[0]['name'];
        $secondItemName = $lw->get('testimonials')[1]['name'];

        $lw->call('moveTestimonialDown', 0);

        $testimonials = json_decode(setting('testimonials') ?? '[]', true);
        $this->assertEquals($secondItemName, $testimonials[0]['name']);
        $this->assertEquals($firstItemName, $testimonials[1]['name']);
    }

    public function test_admin_can_create_faq_via_modal(): void
    {
        $admin = $this->actingAsSuperAdmin();

        Livewire::actingAs($admin)
            ->test(SettingIndex::class)
            ->call('openCreateFaqModal')
            ->assertSet('faqModal', true)
            ->set('faq_question', 'Berapa lama estimasi reparasi TV?')
            ->set('faq_answer', 'Estimasi pengerjaan reparasi TV adalah 1-3 hari kerja tergantung ketersediaan suku cadang.')
            ->call('saveFaq')
            ->assertHasNoErrors()
            ->assertSet('faqModal', false);

        $faqs = json_decode(setting('faqs') ?? '[]', true);
        $this->assertNotEmpty($faqs);
        $last = end($faqs);
        $this->assertEquals('Berapa lama estimasi reparasi TV?', $last['question']);
        $this->assertEquals('Estimasi pengerjaan reparasi TV adalah 1-3 hari kerja tergantung ketersediaan suku cadang.', $last['answer']);
    }

    public function test_admin_can_update_faq_via_modal(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $lw = Livewire::actingAs($admin)->test(SettingIndex::class);
        $lw->call('openEditFaqModal', 0)
            ->assertSet('faqModal', true)
            ->set('faq_question', 'Pertanyaan FAQ Diperbarui?')
            ->set('faq_answer', 'Jawaban FAQ yang telah disesuaikan dengan ketentuan terbaru.')
            ->call('saveFaq')
            ->assertHasNoErrors()
            ->assertSet('faqModal', false);

        $faqs = json_decode(setting('faqs') ?? '[]', true);
        $this->assertEquals('Pertanyaan FAQ Diperbarui?', $faqs[0]['question']);
        $this->assertEquals('Jawaban FAQ yang telah disesuaikan dengan ketentuan terbaru.', $faqs[0]['answer']);
    }

    public function test_admin_can_delete_faq(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $lw = Livewire::actingAs($admin)->test(SettingIndex::class);
        $initialCount = count($lw->get('faqs'));

        $lw->call('confirmDeleteFaq', 0)
            ->assertSet('deleteFaqModal', true)
            ->call('deleteFaq')
            ->assertSet('deleteFaqModal', false);

        $faqs = json_decode(setting('faqs') ?? '[]', true);
        $this->assertCount($initialCount - 1, $faqs);
    }

    public function test_admin_can_reorder_faqs(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $lw = Livewire::actingAs($admin)->test(SettingIndex::class);
        $firstItemQ = $lw->get('faqs')[0]['question'];
        $secondItemQ = $lw->get('faqs')[1]['question'];

        $lw->call('moveFaqDown', 0);

        $faqs = json_decode(setting('faqs') ?? '[]', true);
        $this->assertEquals($secondItemQ, $faqs[0]['question']);
        $this->assertEquals($firstItemQ, $faqs[1]['question']);
    }

    public function test_validation_rules_for_testimonial_and_faq(): void
    {
        $admin = $this->actingAsSuperAdmin();

        Livewire::actingAs($admin)
            ->test(SettingIndex::class)
            ->set('testimonial_name', '')
            ->set('testimonial_quote', '')
            ->call('saveTestimonial')
            ->assertHasErrors(['testimonial_name', 'testimonial_quote'])
            ->set('faq_question', '')
            ->set('faq_answer', '')
            ->call('saveFaq')
            ->assertHasErrors(['faq_question', 'faq_answer']);
    }

    public function test_faq_maximum_limit_enforced_at_five(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $lw = Livewire::actingAs($admin)->test(SettingIndex::class);
        // Initially 3 items, add 4th and 5th
        $lw->set('faq_question', 'FAQ 4')->set('faq_answer', 'Answer 4')->call('saveFaq')->assertHasNoErrors();
        $lw->set('faq_question', 'FAQ 5')->set('faq_answer', 'Answer 5')->call('saveFaq')->assertHasNoErrors();

        $this->assertCount(5, $lw->get('faqs'));

        // Attempt to add 6th
        $lw->call('openCreateFaqModal')->assertSet('faqModal', false);
        $lw->set('faq_question', 'FAQ 6')->set('faq_answer', 'Answer 6')->call('saveFaq');

        $this->assertCount(5, $lw->get('faqs'));
    }
}
