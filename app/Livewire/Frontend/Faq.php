<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class Faq extends Component
{
    public $openIndex = null;

    /**
     * Daftar FAQ — disalin persis dari resources/views/index.html (sebelum refactor).
     * Accordion menggunakan class `.faq-item.open` (managed via CSS + state Livewire).
     */
    public array $items = [
        [
            'q' => 'Bagaimana kondisi elektronik bekas yang dijual?',
            'a' => 'Semua produk telah melalui pengecekan teknisi berpengalaman. Kondisi tertera jelas dengan kategori: Seperti Baru, Kondisi Prima, Kondisi Baik, Lecet Pemakaian, atau Kondisi Minus Body.',
        ],
        [
            'q' => 'Bagaimana proses menjual elektronik saya?',
            'a' => 'Isi formulir di halaman Jual, tim kami menghubungi Anda dengan penawaran. Jika deal, kami jemput gratis ke lokasi dan bayar langsung di tempat.',
        ],
        [
            'q' => 'Apakah garansi berlaku untuk jasa servis?',
            'a' => 'Ya, setiap jasa servis dilengkapi garansi pengerjaan. Jika kerusakan yang sama muncul kembali dalam masa garansi, kami perbaiki tanpa biaya tambahan.',
        ],
    ];

    public function toggle($index): void
    {
        $index = (int) $index;
        $this->openIndex = $this->openIndex === $index ? null : $index;
    }

    public function render()
    {
        return view('livewire.frontend.faq');
    }
}