<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\WithFileUploads;

class SellForm extends Component
{
    use WithFileUploads;

    public $nama = '';
    public $whatsapp = '';
    public $kota = '';
    public $kategori = '';
    public $merek = '';
    public $kondisi = '';
    public $deskripsi = '';
    public $photos = [];
    public $submitted = false;
    public $errorMessage = null;

    protected $rules = [
        'nama' => 'required|string|min:2|max:100',
        'whatsapp' => 'required|string|min:8|max:20',
        'kota' => 'required|string|min:2|max:100',
        'kategori' => 'required|in:tv,kulkas,mesin-cuci,ac,lainnya',
        'merek' => 'required|string|min:2|max:100',
        'kondisi' => 'required|in:baik,cukup,rusak',
        'deskripsi' => 'required|string|min:10|max:1000',
        'photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    ];

    protected $messages = [
        'nama.required' => 'Nama lengkap wajib diisi.',
        'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
        'kota.required' => 'Kota wajib diisi.',
        'kategori.required' => 'Kategori barang wajib dipilih.',
        'merek.required' => 'Merek dan tipe wajib diisi.',
        'kondisi.required' => 'Kondisi barang wajib dipilih.',
        'deskripsi.required' => 'Deskripsi wajib diisi.',
        'deskripsi.min' => 'Deskripsi minimal 10 karakter.',
        'photos.*.image' => 'File harus berupa gambar.',
        'photos.*.max' => 'Ukuran foto maksimal 5MB.',
    ];

    public function submit()
    {
        $this->validate();
        // Simulate submission
        $this->submitted = true;
        $this->reset(['nama', 'whatsapp', 'kota', 'kategori', 'merek', 'kondisi', 'deskripsi', 'photos']);
    }

    public function resetForm()
    {
        $this->submitted = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.frontend.sell-form');
    }
}
