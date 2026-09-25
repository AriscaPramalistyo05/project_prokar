<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View|RedirectResponse
    {
        // Auto-redirect Super Admin to Admin Dashboard when opening the app / homepage
        if (auth()->check() && auth()->user()->hasRole('super_admin') && !request()->has('view_as_guest')) {
            return redirect()->route('admin.dashboard');
        }

        $promoProducts = Product::with(['category', 'primaryImage'])
            ->promo()
            ->available()
            ->take(6)
            ->get();

        if ($promoProducts->isEmpty()) {
            $promoProducts = Product::with(['category', 'primaryImage'])
                ->available()
                ->take(6)
                ->get();
        }

        // ── Hero Section Data ──
        $h1 = setting('hero_headline_1') ?? 'Elektronik Bekas';
        $c1 = setting('hero_headline_color_1') ?? 'hitam';
        $h2 = setting('hero_headline_2') ?? '';
        $c2 = setting('hero_headline_color_2') ?? 'hitam';
        $h3 = setting('hero_headline_3') ?? 'Siap Dipakai';
        $c3 = setting('hero_headline_color_3') ?? 'kuning';

        $hero3CardImg1 = setting('hero_3card_image_1')
            ? optimized_asset(setting('hero_3card_image_1'))
            : 'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=600&h=450&fit=crop&fm=webp&q=80';

        $hero3CardImg2 = setting('hero_3card_image_2')
            ? optimized_asset(setting('hero_3card_image_2'))
            : 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&q=80&fm=webp';

        $hero3CardImg3 = setting('hero_3card_image_3')
            ? optimized_asset(setting('hero_3card_image_3'))
            : asset('storage/settings/hero3card/kO4u7Yrw9y4qsRPqpt0PZKA70LCPAldNxb2ZHgto.webp');

        // ── WhatsApp Number Normalization ──
        $waNumber = preg_replace('/[^0-9]/', '', setting('shop_whatsapp') ?? '089504841279');
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        }

        // ── Testimonials Data ──
        $rawTesti = setting('testimonials');
        $dbTestimonials = is_array($rawTesti) ? $rawTesti : (json_decode($rawTesti ?? '[]', true) ?: []);
        $testiList = !empty($dbTestimonials)
            ? array_map(fn($t) => [
                'text'   => $t['quote'] ?? $t['text'] ?? '',
                'name'   => $t['name'] ?? 'Pelanggan Prokar',
                'role'   => $t['role'] ?? '',
                'rating' => isset($t['rating']) ? (int) $t['rating'] : 5,
            ], $dbTestimonials)
            : [
                ['text' => 'TV yang saya beli kondisinya masih sangat bagus dan sesuai deskripsi. Pengiriman cepat dan pelayanannya ramah', 'name' => 'Ahmad Fauzi', 'role' => 'Pelanggan Elektronik', 'rating' => 5],
                ['text' => 'Kulkas yang saya beli masih sangat dingin dan mulus. Harganya jauh lebih murah dibanding toko biasa, recommended banget!', 'name' => 'Siti Rahayu', 'role' => 'Pembeli Kulkas', 'rating' => 5],
                ['text' => 'Servis mesin cuci saya selesai dalam sehari dan hasilnya memuaskan. Teknisinya profesional dan jujur soal kerusakan.', 'name' => 'Budi Santoso', 'role' => 'Pelanggan Servis Mesin Cuci', 'rating' => 5],
            ];

        // ── FAQ Data ──
        $rawFaqs = setting('faqs');
        $loadedFaqs = is_array($rawFaqs)
            ? $rawFaqs
            : (json_decode($rawFaqs ?? '[]', true) ?: [
                [
                    'question' => 'Bagaimana kondisi elektronik bekas yang dijual?',
                    'answer' => 'Semua produk telah melalui pengecekan teknisi berpengalaman. Kondisi tertera jelas dengan kategori: Seperti Baru, Kondisi Prima, Kondisi Baik, Lecet Pemakaian, atau Kondisi Minus Body.',
                ],
                [
                    'question' => 'Bagaimana proses menjual elektronik saya?',
                    'answer' => 'Isi formulir di halaman Jual, tim kami menghubungi Anda dengan penawaran. Jika deal, kami jemput gratis ke lokasi dan bayar langsung di tempat.',
                ],
                [
                    'question' => 'Apakah garansi berlaku untuk jasa servis?',
                    'answer' => 'Ya, setiap jasa servis dilengkapi garansi pengerjaan. Jika kerusakan yang sama muncul kembali dalam masa garansi, kami perbaiki tanpa biaya tambahan.',
                ],
            ]);
        $faqList = array_map(fn($f) => [
            'question' => $f['question'] ?? $f['q'] ?? '',
            'answer'   => $f['answer'] ?? $f['a'] ?? '',
        ], $loadedFaqs);

        return view('pages.home', compact(
            'promoProducts',
            'h1', 'c1', 'h2', 'c2', 'h3', 'c3',
            'hero3CardImg1', 'hero3CardImg2', 'hero3CardImg3',
            'waNumber',
            'testiList',
            'faqList',
        ));
    }
}
