<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class CheckoutAddressForm extends Component
{
    public string $firstName = '';
    public string $lastName = '';
    public string $address = '';
    public string $apartment = '';
    public string $city = '';
    public string $province = '';
    public string $postalCode = '';
    public string $phone = '';
    public string $email = '';
    public bool $submitted = false;

    protected $rules = [
        'firstName' => 'required|string|min:1|max:60',
        'lastName' => 'required|string|min:1|max:60',
        'address' => 'required|string|min:5|max:200',
        'apartment' => 'nullable|string|max:100',
        'city' => 'required|string|min:2|max:100',
        'province' => 'required|in:DIY,DKI,JB,JTG',
        'postalCode' => 'required|string|min:3|max:10',
        'phone' => 'required|string|min:8|max:20',
        'email' => 'required|email|max:120',
    ];

    protected $messages = [
        'firstName.required' => 'Nama depan wajib diisi.',
        'lastName.required' => 'Nama belakang wajib diisi.',
        'address.required' => 'Alamat wajib diisi.',
        'city.required' => 'Kota wajib diisi.',
        'province.required' => 'Pilih provinsi.',
        'postalCode.required' => 'Kode pos wajib diisi.',
        'phone.required' => 'Nomor telepon wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
    ];

    public function submit(): void
    {
        $this->validate();

        $this->dispatch('address-updated', city: $this->city, postalCode: $this->postalCode);
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
