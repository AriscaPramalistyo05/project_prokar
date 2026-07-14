<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\WithFileUploads;

class SellForm extends Component
{
    use WithFileUploads;

    public $nama = '';
    public $whatsapp = '';
    
    // Address Fields
    public $province_id = '';
    public $regency_id = '';
    public $district_id = '';
    public $village_id = '';
    public $address_detail = '';

    public $kategori = '';
    public $merek = '';
    public $kondisi = '';
    public $deskripsi = '';
    public $media = [];
    public $submitted = false;
    public $errorMessage = null;

    protected function rules()
    {
        return [
            'nama' => 'required|string|min:2|max:100',
            'whatsapp' => ['required', 'string', new \App\Rules\IndonesianPhone()],
            'province_id' => 'required',
            'regency_id' => 'required',
            'district_id' => 'required',
            'village_id' => 'required',
            'address_detail' => 'required|string|min:10',
            'kategori' => 'required|in:tv,kulkas,mesin-cuci,ac,lainnya',
            'merek' => 'required|string|min:2|max:100',
            'kondisi' => 'required|in:baik,cukup,rusak',
            'deskripsi' => 'required|string|min:10|max:1000',
            'media' => 'nullable|array|max:5',
            'media.*' => [
                'required',
                'file',
                'max:20480', // 20MB
                function ($attribute, $value, $fail) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    $imageExts = ['jpg', 'jpeg', 'png', 'webp'];
                    $videoExts = ['mp4', 'mov', 'avi', 'webm'];
                    if (!in_array($extension, array_merge($imageExts, $videoExts))) {
                        $fail('File harus berupa foto (jpg, png, webp) atau video (mp4, mov, avi, webm).');
                    }
                },
            ],
        ];
    }

    protected function messages()
    {
        return [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'province_id.required' => 'Provinsi wajib dipilih.',
            'regency_id.required' => 'Kabupaten/Kota wajib dipilih.',
            'district_id.required' => 'Kecamatan wajib dipilih.',
            'village_id.required' => 'Desa/Kelurahan wajib dipilih.',
            'address_detail.required' => 'Detail alamat wajib diisi.',
            'kategori.required' => 'Kategori barang wajib dipilih.',
            'merek.required' => 'Merek dan tipe wajib diisi.',
            'kondisi.required' => 'Kondisi barang wajib dipilih.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter.',
            'media.max' => 'Maksimal 5 file yang dapat diupload.',
            'media.*.max' => 'Ukuran file maksimal 20MB.',
        ];
    }

    public function mount()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $this->nama = $user->name;
            $this->whatsapp = $user->phone ?? '';
        }
    }

    #[\Livewire\Attributes\On('address-updated')]
    public function updateAddress($data)
    {
        $this->province_id = $data['province_id'];
        $this->regency_id = $data['regency_id'];
        $this->district_id = $data['district_id'];
        $this->village_id = $data['village_id'];
        $this->address_detail = $data['address_detail'];
    }

    public function submit()
    {
        $this->validate();
        // Simulate submission
        $this->submitted = true;
        $this->reset(['nama', 'whatsapp', 'province_id', 'regency_id', 'district_id', 'village_id', 'address_detail', 'kategori', 'merek', 'kondisi', 'deskripsi', 'media']);
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
