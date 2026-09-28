<?php

namespace App\Livewire\Admin;

use App\Models\AdditionalFee;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class AdditionalFeeIndex extends Component
{
    use Toast, WithPagination;

    public $search = '';
    public $fee_modal = false;
    
    // Form fields for modal
    public ?AdditionalFee $fee = null;
    public $name = '';
    public $default_amount = 0;
    public $is_active = true;

    // Dedicated Flat Shipping (Kurir Toko) fields
    public $flat_shipping_cost = 50000;
    public $flat_shipping_active = true;
    public ?int $flat_shipping_fee_id = null;

    public function mount(): void
    {
        $this->loadFlatShippingSetting();
    }

    public function loadFlatShippingSetting(): void
    {
        // Cari atau buat record ongkir kurir toko flat di AdditionalFee
        $flatFee = AdditionalFee::where(function ($q) {
            $q->where('name', 'like', '%ongkir%')
              ->orWhere('name', 'like', '%ongkos kirim%')
              ->orWhere('name', 'like', '%kurir toko%');
        })->first();

        if (!$flatFee) {
            $flatFee = AdditionalFee::create([
                'name' => 'Ongkos Kirim Kurir Toko (Flat)',
                'default_amount' => 50000,
                'is_active' => true,
            ]);
        }

        $this->flat_shipping_fee_id = $flatFee->id;
        $this->flat_shipping_cost = (int) $flatFee->default_amount;
        $this->flat_shipping_active = (bool) $flatFee->is_active;

        // Sinkronisasi awal ke SettingService jika belum ada
        try {
            $settingService = app(\App\Services\SettingService::class);
            if ($settingService->get('flat_shipping_cost') === null) {
                $settingService->set('flat_shipping_cost', $this->flat_shipping_cost, 'shipping', 'number', 'Tarif Ongkir Flat Kurir Toko');
                $settingService->set('flat_shipping_active', $this->flat_shipping_active ? '1' : '0', 'shipping', 'boolean', 'Status Ongkir Flat Aktif');
            }
        } catch (\Throwable $e) {}
    }

    public function saveFlatShipping(): void
    {
        $this->flat_shipping_cost = (float) preg_replace('/[^0-9.]/', '', (string) $this->flat_shipping_cost);

        $this->validate([
            'flat_shipping_cost' => 'required|numeric|min:0',
            'flat_shipping_active' => 'boolean',
        ]);

        if ($this->flat_shipping_fee_id) {
            $flatFee = AdditionalFee::find($this->flat_shipping_fee_id);
            if ($flatFee) {
                $flatFee->update([
                    'default_amount' => $this->flat_shipping_cost,
                    'is_active' => (bool) $this->flat_shipping_active,
                ]);
            }
        } else {
            $this->loadFlatShippingSetting();
            $this->saveFlatShipping();
            return;
        }

        // Sinkronkan ke SettingService untuk performa caching
        try {
            $settingService = app(\App\Services\SettingService::class);
            $settingService->set('flat_shipping_cost', $this->flat_shipping_cost, 'shipping', 'number', 'Tarif Ongkir Flat Kurir Toko');
            $settingService->set('flat_shipping_active', $this->flat_shipping_active ? '1' : '0', 'shipping', 'boolean', 'Status Ongkir Flat Aktif');
        } catch (\Throwable $e) {}

        $this->success('Tarif Ongkir Flat Kurir Toko berhasil disimpan (Rp ' . number_format($this->flat_shipping_cost, 0, ',', '.') . ').');
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Nama Biaya Tambahan'],
            ['key' => 'default_amount', 'label' => 'Nominal Default'],
            ['key' => 'is_active', 'label' => 'Status Aktif'],
            ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-1 text-right'],
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'default_amount' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function create()
    {
        $this->reset(['fee', 'name', 'default_amount', 'is_active']);
        $this->is_active = true;
        $this->default_amount = 0;
        $this->fee_modal = true;
    }

    public function edit(AdditionalFee $fee)
    {
        $this->fee = $fee;
        $this->name = $fee->name;
        $this->default_amount = $fee->default_amount;
        $this->is_active = $fee->is_active;
        $this->fee_modal = true;
    }

    public function save()
    {
        $this->default_amount = (float) preg_replace('/[^0-9.]/', '', (string) $this->default_amount);

        $this->validate();

        $data = [
            'name' => $this->name,
            'default_amount' => $this->default_amount,
            'is_active' => (bool) $this->is_active,
        ];

        if ($this->fee) {
            $this->fee->update($data);

            // Jika yang diedit adalah item ongkir flat, sinkronkan ke state & setting
            if ($this->fee->id === $this->flat_shipping_fee_id || str_contains(strtolower($this->name), 'ongkir') || str_contains(strtolower($this->name), 'kurir toko')) {
                $this->flat_shipping_cost = (int) $this->default_amount;
                $this->flat_shipping_active = (bool) $this->is_active;
                try {
                    $settingService = app(\App\Services\SettingService::class);
                    $settingService->set('flat_shipping_cost', $this->flat_shipping_cost, 'shipping', 'number', 'Tarif Ongkir Flat Kurir Toko');
                    $settingService->set('flat_shipping_active', $this->flat_shipping_active ? '1' : '0', 'shipping', 'boolean', 'Status Ongkir Flat Aktif');
                } catch (\Throwable $e) {}
            }

            $this->success('Biaya tambahan berhasil diperbarui.');
        } else {
            AdditionalFee::create($data);
            $this->success('Biaya tambahan berhasil ditambahkan.');
        }

        $this->fee_modal = false;
    }

    public function delete(AdditionalFee $fee)
    {
        // Cegah penghapusan komponen inti ongkir flat
        if ($fee->id === $this->flat_shipping_fee_id || str_contains(strtolower($fee->name), 'ongkir') || str_contains(strtolower($fee->name), 'kurir toko')) {
            $this->error('Ongkos kirim kurir toko flat adalah komponen pengiriman sistem e-commerce dan tidak dapat dihapus. Anda dapat mengubah nominal atau menonaktifkan statusnya.');
            return;
        }

        $fee->delete();
        $this->success('Biaya tambahan berhasil dihapus.');
    }

    public function render()
    {
        $fees = AdditionalFee::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.additional-fee-index', [
            'fees' => $fees,
            'headers' => $this->headers(),
        ])->layout('layouts.admin');
    }
}
