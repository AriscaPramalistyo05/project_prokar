<?php

namespace App\Livewire\Frontend;

use Livewire\Attributes\Computed;
use Livewire\Component;

class Testimoni extends Component
{
    /**
     * Daftar testimonial. Hanya SATU yang ditampilkan pada satu waktu (slider),
     * dengan navigasi prev/next dan dot indicator.
     */
    public array $testimonials = [
        [
            'text' => 'TV yang saya beli kondisinya masih sangat bagus dan sesuai deskripsi. Pengiriman cepat dan pelayanannya ramah',
            'name' => 'Ahmad Fauzi',
        ],
        [
            'text' => 'Kulkas yang saya beli masih sangat dingin dan mulus. Harganya jauh lebih murah dibanding toko biasa, recommended banget!',
            'name' => 'Siti Rahayu',
        ],
        [
            'text' => 'Servis mesin cuci saya selesai dalam sehari dan hasilnya memuaskan. Teknisinya profesional dan jujur soal kerusakan.',
            'name' => 'Budi Santoso',
        ],
    ];

    public int $currentIndex = 0;

    #[Computed]
    public function currentTestimonial(): array
    {
        return $this->testimonials[$this->currentIndex];
    }

    public function prev(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function next(): void
    {
        if ($this->currentIndex < count($this->testimonials) - 1) {
            $this->currentIndex++;
        }
    }

    public function goTo(int $index): void
    {
        if ($index >= 0 && $index < count($this->testimonials)) {
            $this->currentIndex = $index;
        }
    }

    public function render()
    {
        return view('livewire.frontend.testimoni');
    }
}