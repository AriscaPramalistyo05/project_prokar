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
    
    // Form fields
    public ?AdditionalFee $fee = null;
    public $name = '';
    public $default_amount = 0;
    public $is_active = true;

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
        $this->validate();

        $data = [
            'name' => $this->name,
            'default_amount' => $this->default_amount,
            'is_active' => $this->is_active,
        ];

        if ($this->fee) {
            $this->fee->update($data);
            $this->success('Biaya tambahan berhasil diperbarui.');
        } else {
            AdditionalFee::create($data);
            $this->success('Biaya tambahan berhasil ditambahkan.');
        }

        $this->fee_modal = false;
    }

    public function delete(AdditionalFee $fee)
    {
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
