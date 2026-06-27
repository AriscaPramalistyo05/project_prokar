<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\WithFileUploads;

class ServiceForm extends Component
{
    use WithFileUploads;

    public $nama = '';
    public $whatsapp = '';
    public $kategori = '';
    public $merek = '';
    public $deskripsi = '';
    public $alamat = '';
    public $photos = [];
    public $submitted = false;

    protected $rules = [
        'nama' => 'required|string|min:2|max:100',
        'whatsapp' => 'required|string|min:8|max:20',
        'kategori' => 'required|in:tv,kulkas,mesin-cuci,ac,lainnya',
        'merek' => 'nullable|string|max:100',
        'deskripsi' => 'required|string|min:10|max:1000',
        'alamat' => 'required|string|min:10|max:500',
        'photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    ];

    public function submit()
    {
        $this->validate();
        $this->submitted = true;
        $this->reset(['nama', 'whatsapp', 'kategori', 'merek', 'deskripsi', 'alamat', 'photos']);
    }

    public function resetForm()
    {
        $this->submitted = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.frontend.service-form');
    }
}
