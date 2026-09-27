<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class AddressPicker extends Component
{
    public $province_id = '';
    public $regency_id = '';
    public $district_id = '';
    public $village_id = '';
    public $address_detail = '';

    // Names for display
    public $province_name = '';
    public $regency_name = '';
    public $district_name = '';
    public $village_name = '';

    // Styling Props
    public $inputClass = 'w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-black';
    public $labelClass = 'block text-xs font-bold text-gray-700 mb-1';

    public function mount($initialData = [], $inputClass = null, $labelClass = null)
    {
        if ($inputClass) $this->inputClass = $inputClass;
        if ($labelClass) $this->labelClass = $labelClass;

        if (isset($initialData['province_id'])) $this->province_id = $initialData['province_id'];
        if (isset($initialData['regency_id'])) $this->regency_id = $initialData['regency_id'];
        if (isset($initialData['district_id'])) $this->district_id = $initialData['district_id'];
        if (isset($initialData['village_id'])) $this->village_id = $initialData['village_id'];
        if (isset($initialData['address_detail'])) $this->address_detail = $initialData['address_detail'];
        if (isset($initialData['province_name'])) $this->province_name = $initialData['province_name'];
        if (isset($initialData['regency_name'])) $this->regency_name = $initialData['regency_name'];
        if (isset($initialData['district_name'])) $this->district_name = $initialData['district_name'];
        if (isset($initialData['village_name'])) $this->village_name = $initialData['village_name'];
    }

    public function updated($propertyName)
    {
        $this->dispatch('address-updated', [
            'province_id' => $this->province_id,
            'regency_id' => $this->regency_id,
            'district_id' => $this->district_id,
            'village_id' => $this->village_id,
            'province_name' => $this->province_name,
            'regency_name' => $this->regency_name,
            'district_name' => $this->district_name,
            'village_name' => $this->village_name,
            'address_detail' => $this->address_detail,
        ]);
    }

    public static array $provinces = [
        ['id' => '11', 'name' => 'ACEH'],
        ['id' => '12', 'name' => 'SUMATERA UTARA'],
        ['id' => '13', 'name' => 'SUMATERA BARAT'],
        ['id' => '14', 'name' => 'RIAU'],
        ['id' => '15', 'name' => 'JAMBI'],
        ['id' => '16', 'name' => 'SUMATERA SELATAN'],
        ['id' => '17', 'name' => 'BENGKULU'],
        ['id' => '18', 'name' => 'LAMPUNG'],
        ['id' => '19', 'name' => 'KEPULAUAN BANGKA BELITUNG'],
        ['id' => '21', 'name' => 'KEPULAUAN RIAU'],
        ['id' => '31', 'name' => 'DKI JAKARTA'],
        ['id' => '32', 'name' => 'JAWA BARAT'],
        ['id' => '33', 'name' => 'JAWA TENGAH'],
        ['id' => '34', 'name' => 'DI YOGYAKARTA'],
        ['id' => '35', 'name' => 'JAWA TIMUR'],
        ['id' => '36', 'name' => 'BANTEN'],
        ['id' => '51', 'name' => 'BALI'],
        ['id' => '52', 'name' => 'NUSA TENGGARA BARAT'],
        ['id' => '53', 'name' => 'NUSA TENGGARA TIMUR'],
        ['id' => '61', 'name' => 'KALIMANTAN BARAT'],
        ['id' => '62', 'name' => 'KALIMANTAN TENGAH'],
        ['id' => '63', 'name' => 'KALIMANTAN SELATAN'],
        ['id' => '64', 'name' => 'KALIMANTAN TIMUR'],
        ['id' => '65', 'name' => 'KALIMANTAN UTARA'],
        ['id' => '71', 'name' => 'SULAWESI UTARA'],
        ['id' => '72', 'name' => 'SULAWESI TENGAH'],
        ['id' => '73', 'name' => 'SULAWESI SELATAN'],
        ['id' => '74', 'name' => 'SULAWESI TENGGARA'],
        ['id' => '75', 'name' => 'GORONTALO'],
        ['id' => '76', 'name' => 'SULAWESI BARAT'],
        ['id' => '81', 'name' => 'MALUKU'],
        ['id' => '82', 'name' => 'MALUKU UTARA'],
        ['id' => '91', 'name' => 'PAPUA BARAT'],
        ['id' => '94', 'name' => 'PAPUA'],
    ];

    public function render()
    {
        return view('livewire.frontend.address-picker', [
            'provincesList' => self::$provinces,
        ]);
    }
}
