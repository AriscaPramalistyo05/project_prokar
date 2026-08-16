<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class CheckoutAddressForm extends Component
{
    public string $name = '';
    
    // Address Fields
    public $province_id = '';
    public $regency_id = '';
    public $district_id = '';
    public $village_id = '';
    public $address_detail = '';

    public string $phone = '';
    public string $email = '';
    public bool $submitted = false;

    protected $rules = [
        'name' => 'required|string|min:1|max:120',
        'province_id' => 'required',
        'regency_id' => 'required',
        'district_id' => 'required',
        'village_id' => 'required',
        'address_detail' => 'required|string|min:5',
        'phone' => ['required', 'string', new \App\Rules\IndonesianPhone()],
        'email' => 'required|email|max:120',
    ];

    protected $messages = [
        'name.required' => 'Nama lengkap wajib diisi.',
        'province_id.required' => 'Provinsi wajib dipilih.',
        'regency_id.required' => 'Kabupaten/Kota wajib dipilih.',
        'district_id.required' => 'Kecamatan wajib dipilih.',
        'village_id.required' => 'Desa/Kelurahan wajib dipilih.',
        'address_detail.required' => 'Detail alamat wajib diisi.',
        'phone.required' => 'Nomor telepon wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
    ];

    public function mount()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $this->name = $user->name ?? '';
            $this->phone = $user->phone ?? '';
            $this->email = $user->email ?? '';
        }
    }

    #[\Livewire\Attributes\On('address-updated')]
    public function updateAddress($data)
    {
        if (isset($data['province_id'])) {
            $this->province_id = $data['province_id'];
            $this->regency_id = $data['regency_id'];
            $this->district_id = $data['district_id'];
            $this->village_id = $data['village_id'];
            $this->address_detail = $data['address_detail'];
        }
    }

    public function submit(): void
    {
        $this->validate();

        $this->dispatch('checkout-address-updated', [
            'province_id' => $this->province_id,
            'regency_id' => $this->regency_id,
            'district_id' => $this->district_id,
            'village_id' => $this->village_id,
            'address_detail' => $this->address_detail,
        ]);
        $this->submitted = true;
    }

    public function formatRupiah(int $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.') . ',00';
    }

    public function render()
    {
        return view('livewire.frontend.checkout-address-form');
    }
}
