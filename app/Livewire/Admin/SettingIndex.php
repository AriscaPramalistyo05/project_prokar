<?php

namespace App\Livewire\Admin;

use App\Services\FcmNotificationService;
use App\Services\SettingService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Layout('layouts.admin')]
class SettingIndex extends Component
{
    use WithFileUploads, Toast;

    public string $selectedTab = 'general-tab';

    // ─── TAB 1: UMUM & IDENTITAS ─────────────────────────────────────
    public string $shop_name = '';
    public string $shop_tagline = '';
    public string $shop_address = '';
    public string $shop_maps_embed = '';
    public string $shop_whatsapp = '';
    public string $shop_phone = '';
    public string $shop_email = '';
    public string $shop_opening_hours = '';
    public string $social_instagram = '';
    public string $social_tiktok = '';
    public string $social_facebook = '';
    public string $social_youtube = '';
    public $logo_file = null;
    public ?string $existing_logo = null;
    public $favicon_file = null;
    public ?string $existing_favicon = null;

    // ─── TAB 2: TAMPILAN & KONTEN HOME ───────────────────────────────
    // Hero Section
    public string $hero_badge = '';
    public string $hero_headline = '';
    public string $hero_headline_1 = 'JUAL, BELI & SERVIS';
    public string $hero_headline_color_1 = 'kuning'; // 'hitam', 'kuning', 'biru'
    public string $hero_headline_2 = 'ELEKTRONIK BEKAS';
    public string $hero_headline_color_2 = 'hitam'; // 'hitam', 'kuning', 'biru'
    public string $hero_headline_3 = 'TERPERCAYA';
    public string $hero_headline_color_3 = 'biru'; // 'hitam', 'kuning', 'biru'
    public string $hero_subheadline = '';
    public string $hero_card_mode = '6_card'; // '6_card' or '3_card'

    // 6 Hero Category Gallery Images (Mode 6 Card)
    public $hero_image_kulkas_file = null;
    public ?string $existing_hero_image_kulkas = null;

    public $hero_image_tv_file = null;
    public ?string $existing_hero_image_tv = null;

    public $hero_image_mesin_cuci_file = null;
    public ?string $existing_hero_image_mesin_cuci = null;

    public $hero_image_dispenser_file = null;
    public ?string $existing_hero_image_dispenser = null;

    public $hero_image_microwave_file = null;
    public ?string $existing_hero_image_microwave = null;

    public $hero_image_ac_file = null;
    public ?string $existing_hero_image_ac = null;

    // 3 Hero Collage Cards (Mode 3 Card)
    public $hero_3card_image_1_file = null;
    public ?string $existing_hero_3card_image_1 = null;
    public string $hero_3card_title_1 = '';

    public $hero_3card_image_2_file = null;
    public ?string $existing_hero_3card_image_2 = null;
    public string $hero_3card_title_2 = '';

    public $hero_3card_image_3_file = null;
    public ?string $existing_hero_3card_image_3 = null;
    public string $hero_3card_title_3 = '';

    // Running Text & Brand Partners
    public string $marquee_text_black = '';
    public string $marquee_text_blue = '';
    public string $brand_partners = '';

    // 3 Service Card Images
    public $service_image_tv_file = null;
    public ?string $existing_service_image_tv = null;

    public $service_image_mesin_cuci_file = null;
    public ?string $existing_service_image_mesin_cuci = null;

    public $service_image_kulkas_file = null;
    public ?string $existing_service_image_kulkas = null;

    // Layanan Lainnya Box
    public string $service_other_title = '';
    public string $service_other_desc = '';

    // Testimoni Pelanggan
    public array $testimonials = [];

    // FAQ (Pertanyaan Umum)
    public array $faqs = [];

    // ─── TAB 3: EMAIL (SMTP) ─────────────────────────────────────────
    public string $mail_host = '';
    public string $mail_port = '';
    public string $mail_username = '';
    public string $mail_password = '';
    public string $mail_from_address = '';
    public string $mail_from_name = '';
    public string $mail_encryption = 'tls';

    // ─── TAB 4: PAYMENT (MIDTRANS) ───────────────────────────────────
    public string $midtrans_merchant_id = '';
    public string $midtrans_server_key = '';
    public string $midtrans_client_key = '';
    public bool $midtrans_is_production = false;

    // ─── TAB 5: NOTIFIKASI (FCM / FIREBASE) ──────────────────────────
    public string $firebase_api_key = '';
    public string $firebase_project_id = '';
    public string $firebase_messaging_sender_id = '';
    public string $firebase_app_id = '';
    public string $firebase_vapid_key = '';
    public $service_account_file = null;
    public bool $has_service_account_file = false;

    // ─── TAB 6: GARANSI ──────────────────────────────────────────────
    public int $warranty_duration_days = 30;
    public string $warranty_terms = '';

    public function mount(SettingService $settingService): void
    {
        // 1. Umum & Identitas
        $this->shop_name = (string) ($settingService->get('shop_name') ?? 'Prokar Elektronik');
        $this->shop_tagline = (string) ($settingService->get('shop_tagline') ?? 'Jual · Beli · Servis Elektronik Bekas Terpercaya');
        $this->shop_address = (string) ($settingService->get('shop_address') ?? 'Karanggondang, Rt4 Rw2, Mlonggo, Jepara, Jawa Tengah 59452');
        $this->shop_maps_embed = (string) ($settingService->get('shop_maps_embed') ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.0545985815284!2d110.71228237499275!3d-6.514773893477648!2m3!1f0!2f0!3f0!2m3!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7123e1adf86edb%3A0xc0e7d2d2ad9056d3!2sProkar%20Elektronik!5e0!3m2!1sen!2sid!4v1780388610597!5m2!1sen!2sid');
        $this->shop_whatsapp = (string) ($settingService->get('shop_whatsapp') ?? '089504841279');
        $this->shop_phone = (string) ($settingService->get('shop_phone') ?? '0895-0484-1279');
        $this->shop_email = (string) ($settingService->get('shop_email') ?? 'support@prokar.id');
        $this->shop_opening_hours = (string) ($settingService->get('shop_opening_hours') ?? 'Senin - Sabtu : 08.00 - 21.00');
        $this->social_instagram = (string) ($settingService->get('social_instagram') ?? 'https://instagram.com/prokar.elektronik');
        $this->social_tiktok = (string) ($settingService->get('social_tiktok') ?? '');
        $this->social_facebook = (string) ($settingService->get('social_facebook') ?? '');
        $this->social_youtube = (string) ($settingService->get('social_youtube') ?? '');
        $this->existing_logo = $settingService->get('shop_logo');
        $this->existing_favicon = $settingService->get('shop_favicon');

        // 2. Tampilan & Konten Home
        $this->hero_badge = (string) ($settingService->get('hero_badge') ?? 'Bergaransi & Berkualitas');
        $this->hero_headline = (string) ($settingService->get('hero_headline') ?? 'JUAL, BELI & SERVIS ELEKTRONIK BEKAS TERPERCAYA');
        $this->hero_headline_1 = (string) ($settingService->get('hero_headline_1') ?? 'JUAL, BELI & SERVIS');
        $this->hero_headline_color_1 = (string) ($settingService->get('hero_headline_color_1') ?? 'kuning');
        $this->hero_headline_2 = (string) ($settingService->get('hero_headline_2') ?? 'ELEKTRONIK BEKAS');
        $this->hero_headline_color_2 = (string) ($settingService->get('hero_headline_color_2') ?? 'hitam');
        $this->hero_headline_3 = (string) ($settingService->get('hero_headline_3') ?? 'TERPERCAYA');
        $this->hero_headline_color_3 = (string) ($settingService->get('hero_headline_color_3') ?? 'biru');
        $this->hero_subheadline = (string) ($settingService->get('hero_subheadline') ?? 'Beragam elektronik rumah tangga berkualitas yang siap digunakan dan telah melalui proses pengecekan teknisi profesional.');
        $this->hero_card_mode = (string) ($settingService->get('hero_card_mode') ?? '6_card');
        
        // 6 Hero Category Images (Mode 6 Card)
        $this->existing_hero_image_kulkas = $settingService->get('hero_image_kulkas');
        $this->existing_hero_image_tv = $settingService->get('hero_image_tv');
        $this->existing_hero_image_mesin_cuci = $settingService->get('hero_image_mesin_cuci');
        $this->existing_hero_image_dispenser = $settingService->get('hero_image_dispenser');
        $this->existing_hero_image_microwave = $settingService->get('hero_image_microwave');
        $this->existing_hero_image_ac = $settingService->get('hero_image_ac');

        // 3 Hero Collage Cards (Mode 3 Card)
        $this->existing_hero_3card_image_1 = $settingService->get('hero_3card_image_1');
        $this->existing_hero_3card_image_2 = $settingService->get('hero_3card_image_2');
        $this->existing_hero_3card_image_3 = $settingService->get('hero_3card_image_3');
        $this->hero_3card_title_1 = (string) ($settingService->get('hero_3card_title_1') ?? 'Mesin Cuci');
        $this->hero_3card_title_2 = (string) ($settingService->get('hero_3card_title_2') ?? 'Televisi ');
        $this->hero_3card_title_3 = (string) ($settingService->get('hero_3card_title_3') ?? 'Kulkas');

        $this->marquee_text_black = (string) ($settingService->get('marquee_text_black') ?? 'PRODUK BERGARANSI ★ KUALITAS TERUJI ★ TEKNISI BERPENGALAMAN ★ BISA COD ★');
        $this->marquee_text_blue = (string) ($settingService->get('marquee_text_blue') ?? 'tersedia berbagai produk elektronik rumah tangga • harga ramah barang berkualitas');
        $this->brand_partners = (string) ($settingService->get('brand_partners') ?? 'SHARP, POLYTRON, LG, AQUA, SAMSUNG, Panasonic, TOSHIBA, Hisense');
        
        // 3 Service Card Images
        $this->existing_service_image_tv = $settingService->get('service_image_tv');
        $this->existing_service_image_mesin_cuci = $settingService->get('service_image_mesin_cuci');
        $this->existing_service_image_kulkas = $settingService->get('service_image_kulkas');

        // Layanan Lainnya Box
        $this->service_other_title = (string) ($settingService->get('service_other_title') ?? 'Layanan Lainnya');
        $this->service_other_desc = (string) ($settingService->get('service_other_desc') ?? 'Kami juga menerima reparasi AC, Setrika, Speaker, dan peralatan elektronik lainnya.');
        
        // Testimonials
        $rawTestimonials = $settingService->get('testimonials');
        $this->testimonials = is_array($rawTestimonials) ? $rawTestimonials : (json_decode($rawTestimonials ?? '[]', true) ?: [
            [
                'name' => 'Ahmad Fauzi',
                'quote' => 'TV yang saya beli kondisinya masih sangat bagus dan sesuai deskripsi. Pengiriman cepat dan pelayanannya ramah',
            ],
            [
                'name' => 'Siti Rahayu',
                'quote' => 'Kulkas yang saya beli masih sangat dingin dan mulus. Harganya jauh lebih murah dibanding toko biasa, recommended banget!',
            ],
            [
                'name' => 'Budi Santoso',
                'quote' => 'Servis mesin cuci saya selesai dalam sehari dan hasilnya memuaskan. Teknisinya profesional dan jujur soal kerusakan.',
            ]
        ]);

        // FAQs
        $rawFaqs = $settingService->get('faqs');
        $this->faqs = is_array($rawFaqs) ? $rawFaqs : (json_decode($rawFaqs ?? '[]', true) ?: [
            [
                'question' => 'Bagaimana kondisi elektronik bekas yang dijual?',
                'answer'   => 'Semua produk telah melalui pengecekan teknisi berpengalaman. Kondisi tertera jelas dengan kategori: Seperti Baru, Kondisi Prima, Kondisi Baik, Lecet Pemakaian, atau Kondisi Minus Body.',
            ],
            [
                'question' => 'Bagaimana proses menjual elektronik saya?',
                'answer'   => 'Isi formulir di halaman Jual, tim kami menghubungi Anda dengan penawaran. Jika deal, kami jemput gratis ke lokasi dan bayar langsung di tempat.',
            ],
            [
                'question' => 'Apakah garansi berlaku untuk jasa servis?',
                'answer'   => 'Ya, setiap jasa servis dilengkapi garansi pengerjaan. Jika kerusakan yang sama muncul kembali dalam masa garansi, kami perbaiki tanpa biaya tambahan.',
            ],
        ]);

        // 3. Email (SMTP)
        $this->mail_host = (string) ($settingService->get('mail_host') ?? config('mail.mailers.smtp.host', 'smtp.gmail.com'));
        $this->mail_port = (string) ($settingService->get('mail_port') ?? config('mail.mailers.smtp.port', '587'));
        $this->mail_username = (string) ($settingService->get('mail_username') ?? config('mail.mailers.smtp.username', ''));
        $this->mail_password = (string) ($settingService->get('mail_password', true) ?? '');
        $this->mail_from_address = (string) ($settingService->get('mail_from_address') ?? config('mail.from.address', 'support@prokar.id'));
        $this->mail_from_name = (string) ($settingService->get('mail_from_name') ?? config('mail.from.name', 'Prokar Elektronik'));
        $this->mail_encryption = (string) ($settingService->get('mail_encryption') ?? config('mail.mailers.smtp.encryption', 'tls'));

        // 4. Payment (Midtrans)
        $this->midtrans_merchant_id = (string) ($settingService->get('midtrans_merchant_id') ?? config('midtrans.merchant_id', ''));
        $this->midtrans_server_key = (string) ($settingService->get('midtrans_server_key', true) ?? config('midtrans.server_key', ''));
        $this->midtrans_client_key = (string) ($settingService->get('midtrans_client_key', true) ?? config('midtrans.client_key', ''));
        $this->midtrans_is_production = (bool) ($settingService->get('midtrans_is_production') ?? config('midtrans.is_production', false));

        // 5. Notifikasi (FCM / Firebase)
        $this->firebase_api_key = (string) ($settingService->get('firebase_api_key') ?? '');
        $this->firebase_project_id = (string) ($settingService->get('firebase_project_id') ?? '');
        $this->firebase_messaging_sender_id = (string) ($settingService->get('firebase_messaging_sender_id') ?? '');
        $this->firebase_app_id = (string) ($settingService->get('firebase_app_id') ?? '');
        $this->firebase_vapid_key = (string) ($settingService->get('firebase_vapid_key') ?? '');
        $this->has_service_account_file = file_exists(storage_path('app/firebase/service-account.json')) || file_exists(base_path('storage/app/firebase/service-account.json'));

        // 6. Garansi
        $this->warranty_duration_days = (int) ($settingService->get('warranty_duration_days') ?? 30);
        $this->warranty_terms = (string) ($settingService->get('warranty_terms') ?? "1. Garansi mencakup kerusakan fungsi mesin bukan akibat kelalaian pemakaian.\n2. Segel toko pada unit barang/servis harus dalam keadaan utuh.\n3. Harap simpan invoice atau kartu garansi resmi digital ini.");
    }

    public function addTestimonial(): void
    {
        $this->testimonials[] = [
            'name'  => '',
            'quote' => '',
        ];
    }

    public function removeTestimonial(int $index): void
    {
        unset($this->testimonials[$index]);
        $this->testimonials = array_values($this->testimonials);
    }

    public function addFaq(): void
    {
        $this->faqs[] = [
            'question' => '',
            'answer'   => '',
        ];
    }

    public function removeFaq(int $index): void
    {
        unset($this->faqs[$index]);
        $this->faqs = array_values($this->faqs);
    }

    public function save(SettingService $settingService): void
    {
        $this->validate([
            'shop_name'     => 'required|string|max:100',
            'shop_whatsapp' => 'required|string|max:30',
            'shop_email'    => 'required|email|max:100',
            'logo_file'     => 'nullable|image|max:2048',
            'favicon_file'  => 'nullable|image|max:1024',
            'hero_image_kulkas_file' => 'nullable|image|max:4096',
            'hero_image_tv_file' => 'nullable|image|max:4096',
            'hero_image_mesin_cuci_file' => 'nullable|image|max:4096',
            'hero_image_dispenser_file' => 'nullable|image|max:4096',
            'hero_image_microwave_file' => 'nullable|image|max:4096',
            'hero_image_ac_file' => 'nullable|image|max:4096',
            'service_image_tv_file' => 'nullable|image|max:4096',
            'service_image_mesin_cuci_file' => 'nullable|image|max:4096',
            'service_image_kulkas_file' => 'nullable|image|max:4096',
            'service_account_file' => 'nullable|file|mimes:json,txt|max:2048',
        ]);

        // Upload Logo Utama
        if ($this->logo_file) {
            $path = $this->logo_file->store('settings', 'public');
            $settingService->set('shop_logo', $path, 'general', 'image', 'Logo Utama Toko');
            $this->existing_logo = $path;
            $this->logo_file = null;
        }

        // Upload Favicon
        if ($this->favicon_file) {
            $path = $this->favicon_file->store('settings', 'public');
            $settingService->set('shop_favicon', $path, 'general', 'image', 'Favicon Toko');
            $this->existing_favicon = $path;
            $this->favicon_file = null;
        }

        // Upload 6 Hero Category Images
        $heroCategories = [
            'kulkas' => 'Kulkas',
            'tv' => 'TV',
            'mesin_cuci' => 'Mesin Cuci',
            'dispenser' => 'Dispenser',
            'microwave' => 'Microwave',
            'ac' => 'AC',
        ];

        foreach ($heroCategories as $key => $label) {
            $fileProp = "hero_image_{$key}_file";
            $existProp = "existing_hero_image_{$key}";
            if ($this->$fileProp) {
                $path = \App\Services\ImageOptimizer::optimizeAndStore($this->$fileProp, 'settings/hero', 800, 80);
                $settingService->set("hero_image_{$key}", $path, 'homepage', 'image', "Hero Banner {$label}");
                $this->$existProp = $path;
                $this->$fileProp = null;
            }
        }

        // Upload 3 Hero Collage Cards (Mode 3 Card)
        for ($i = 1; $i <= 3; $i++) {
            $fileProp = "hero_3card_image_{$i}_file";
            $existProp = "existing_hero_3card_image_{$i}";
            if ($this->$fileProp) {
                $path = \App\Services\ImageOptimizer::optimizeAndStore($this->$fileProp, 'settings/hero3card', 1000, 80);
                $settingService->set("hero_3card_image_{$i}", $path, 'homepage', 'image', "Hero 3-Card Foto {$i}");
                $this->$existProp = $path;
                $this->$fileProp = null;
            }
        }

        // Upload 3 Service Card Images
        $serviceCards = [
            'tv' => 'TV',
            'mesin_cuci' => 'Mesin Cuci',
            'kulkas' => 'Kulkas',
        ];

        foreach ($serviceCards as $key => $label) {
            $fileProp = "service_image_{$key}_file";
            $existProp = "existing_service_image_{$key}";
            if ($this->$fileProp) {
                $path = \App\Services\ImageOptimizer::optimizeAndStore($this->$fileProp, 'settings/service', 800, 80);
                $settingService->set("service_image_{$key}", $path, 'homepage', 'image', "Foto Service {$label}");
                $this->$existProp = $path;
                $this->$fileProp = null;
            }
        }

        // Upload Firebase Service Account JSON
        if ($this->service_account_file) {
            $targetDir = storage_path('app/firebase');
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $this->service_account_file->storeAs('firebase', 'service-account.json');
            $this->has_service_account_file = true;
            $this->service_account_file = null;
        }

        // 1. Simpan Tab Umum
        $settingService->set('shop_name', $this->shop_name, 'general', 'text', 'Nama Toko');
        $settingService->set('shop_tagline', $this->shop_tagline, 'general', 'text', 'Tagline Toko');
        $settingService->set('shop_address', $this->shop_address, 'general', 'textarea', 'Alamat Toko');
        $settingService->set('shop_maps_embed', $this->shop_maps_embed, 'general', 'text', 'Link Embed Google Maps');
        $settingService->set('shop_whatsapp', $this->shop_whatsapp, 'general', 'text', 'Nomor WhatsApp CS');
        $settingService->set('shop_phone', $this->shop_phone, 'general', 'text', 'Nomor Telepon');
        $settingService->set('shop_email', $this->shop_email, 'general', 'text', 'Email Resmi');
        $settingService->set('shop_opening_hours', $this->shop_opening_hours, 'general', 'text', 'Jam Operasional');
        $settingService->set('social_instagram', $this->social_instagram, 'general', 'text', 'Instagram');
        $settingService->set('social_tiktok', $this->social_tiktok, 'general', 'text', 'TikTok');
        $settingService->set('social_facebook', $this->social_facebook, 'general', 'text', 'Facebook');
        $settingService->set('social_youtube', $this->social_youtube, 'general', 'text', 'YouTube');

        // 2. Simpan Tab Tampilan & Konten Home
        $settingService->set('hero_card_mode', $this->hero_card_mode, 'homepage', 'text', 'Mode Card Hero (6_card / 3_card)');
        $settingService->set('hero_badge', $this->hero_badge, 'homepage', 'text', 'Hero Badge Text');
        $settingService->set('hero_headline_1', $this->hero_headline_1, 'homepage', 'text', 'Headline Hero Bagian 1');
        $settingService->set('hero_headline_color_1', $this->hero_headline_color_1, 'homepage', 'text', 'Warna Headline Bagian 1');
        $settingService->set('hero_headline_2', $this->hero_headline_2, 'homepage', 'text', 'Headline Hero Bagian 2');
        $settingService->set('hero_headline_color_2', $this->hero_headline_color_2, 'homepage', 'text', 'Warna Headline Bagian 2');
        $settingService->set('hero_headline_3', $this->hero_headline_3, 'homepage', 'text', 'Headline Hero Bagian 3');
        $settingService->set('hero_headline_color_3', $this->hero_headline_color_3, 'homepage', 'text', 'Warna Headline Bagian 3');
        $combinedHeadline = trim("{$this->hero_headline_1} {$this->hero_headline_2} {$this->hero_headline_3}");
        $settingService->set('hero_headline', $combinedHeadline, 'homepage', 'text', 'Hero Headline');
        $settingService->set('hero_subheadline', $this->hero_subheadline, 'homepage', 'textarea', 'Hero Subheadline');
        $settingService->set('hero_3card_title_1', $this->hero_3card_title_1, 'homepage', 'text', 'Judul Hero 3-Card 1');
        $settingService->set('hero_3card_title_2', $this->hero_3card_title_2, 'homepage', 'text', 'Judul Hero 3-Card 2');
        $settingService->set('hero_3card_title_3', $this->hero_3card_title_3, 'homepage', 'text', 'Judul Hero 3-Card 3');
        $settingService->set('marquee_text_black', $this->marquee_text_black, 'homepage', 'text', 'Marquee Hitam');
        $settingService->set('marquee_text_blue', $this->marquee_text_blue, 'homepage', 'text', 'Marquee Biru');
        $settingService->set('brand_partners', $this->brand_partners, 'homepage', 'text', 'Brand Partner');
        $settingService->set('service_other_title', $this->service_other_title, 'homepage', 'text', 'Judul Layanan Lainnya');
        $settingService->set('service_other_desc', $this->service_other_desc, 'homepage', 'textarea', 'Deskripsi Layanan Lainnya');
        $settingService->set('testimonials', json_encode(array_values($this->testimonials)), 'homepage', 'json', 'Testimoni Pelanggan');
        $settingService->set('faqs', json_encode(array_values($this->faqs)), 'homepage', 'json', 'Pertanyaan Umum (FAQ)');

        // 3. Simpan Tab Email (SMTP)
        $settingService->set('mail_host', $this->mail_host, 'mail', 'text', 'SMTP Host');
        $settingService->set('mail_port', $this->mail_port, 'mail', 'text', 'SMTP Port');
        $settingService->set('mail_username', $this->mail_username, 'mail', 'text', 'SMTP Username');
        if (!empty($this->mail_password)) {
            $settingService->set('mail_password', $this->mail_password, 'mail', 'text', 'SMTP Password');
        }
        $settingService->set('mail_from_address', $this->mail_from_address, 'mail', 'text', 'Mail From Address');
        $settingService->set('mail_from_name', $this->mail_from_name, 'mail', 'text', 'Mail From Name');
        $settingService->set('mail_encryption', $this->mail_encryption, 'mail', 'text', 'Mail Encryption');

        // 4. Simpan Tab Payment (Midtrans)
        $settingService->set('midtrans_merchant_id', $this->midtrans_merchant_id, 'payment', 'text', 'Midtrans Merchant ID');
        if (!empty($this->midtrans_server_key)) {
            $settingService->set('midtrans_server_key', $this->midtrans_server_key, 'payment', 'text', 'Midtrans Server Key');
        }
        if (!empty($this->midtrans_client_key)) {
            $settingService->set('midtrans_client_key', $this->midtrans_client_key, 'payment', 'text', 'Midtrans Client Key');
        }
        $settingService->set('midtrans_is_production', $this->midtrans_is_production ? '1' : '0', 'payment', 'boolean', 'Midtrans Production Mode');

        // 5. Simpan Tab Notifikasi (Firebase FCM)
        $settingService->set('firebase_api_key', $this->firebase_api_key, 'notification', 'text', 'Firebase API Key');
        $settingService->set('firebase_project_id', $this->firebase_project_id, 'notification', 'text', 'Firebase Project ID');
        $settingService->set('firebase_messaging_sender_id', $this->firebase_messaging_sender_id, 'notification', 'text', 'Firebase Sender ID');
        $settingService->set('firebase_app_id', $this->firebase_app_id, 'notification', 'text', 'Firebase App ID');
        $settingService->set('firebase_vapid_key', $this->firebase_vapid_key, 'notification', 'text', 'Firebase VAPID Key');

        // 6. Simpan Tab Garansi
        $settingService->set('warranty_duration_days', (string) $this->warranty_duration_days, 'warranty', 'text', 'Durasi Garansi Toko');
        $settingService->set('warranty_terms', $this->warranty_terms, 'warranty', 'textarea', 'Syarat & Ketentuan Garansi');

        $this->success('Pengaturan toko & beranda berhasil disimpan!');
    }

    public function testEmail(): void
    {
        $targetEmail = auth()->user()->email ?? $this->shop_email;

        try {
            Mail::raw('Ini adalah email pengujian (Test Email) dari Pengaturan Sistem Prokar Elektronik. Koneksi SMTP Anda berhasil terhubung dengan sempurna!', function ($message) use ($targetEmail) {
                $message->to($targetEmail)
                    ->subject('Test Koneksi SMTP — Prokar Elektronik');
            });

            $this->success("Email uji coba berhasil dikirim ke {$targetEmail}!");
        } catch (\Exception $e) {
            $this->error('Gagal mengirim email: ' . $e->getMessage());
        }
    }

    public function testMidtrans(): void
    {
        $serverKey = !empty($this->midtrans_server_key) ? $this->midtrans_server_key : setting('midtrans_server_key', true);

        if (empty($serverKey)) {
            $this->error('Server Key Midtrans belum diisi!');
            return;
        }

        try {
            $baseUrl = $this->midtrans_is_production 
                ? 'https://api.midtrans.com/v2' 
                : 'https://api.sandbox.midtrans.com/v2';

            $response = Http::withBasicAuth($serverKey, '')
                ->get("{$baseUrl}/dummy-order-check/status");

            if ($response->status() === 401) {
                $this->error('Kredensial Server Key Midtrans tidak valid / ditolak oleh Midtrans (401 Unauthorized).');
            } else {
                $this->success('Koneksi ke server Midtrans berhasil! Kunci API valid.');
            }
        } catch (\Exception $e) {
            $this->error('Gagal terhubung ke Midtrans: ' . $e->getMessage());
        }
    }

    public function testFcm(FcmNotificationService $fcmService): void
    {
        try {
            $fcmService->sendToAdmins(
                'Test Notifikasi Admin',
                'Ini adalah notifikasi uji coba Firebase Cloud Messaging dari Pengaturan Prokar Elektronik.',
                ['type' => 'test']
            );

            $this->success('Perintah push notifikasi berhasil dikirim ke perangkat admin yang terdaftar!');
        } catch (\Exception $e) {
            $this->error('Gagal mengirim push notifikasi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.setting-index');
    }
}
