<?php

namespace App\Livewire\Admin;

use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class ServiceIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $tab = 'baru';

    #[Url]
    public string $search = '';

    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTab()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ServiceOrder::query()->with(['category', 'technician']);

        // Role-based access
        if (Auth::user()->hasRole('teknisi')) {
            $query->where('technician_id', Auth::id());
        }

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('service_code', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
            });
        }

        // Tab filter
        switch ($this->tab) {
            case 'baru':
                $query->where('status', 'pending');
                break;
            case 'proses':
                $query->whereIn('status', ['confirmed', 'diagnosing', 'waiting_approval', 'in_progress']);
                break;
            case 'selesai':
                $query->where('status', 'completed');
                break;
            case 'batal':
                $query->where('status', 'cancelled');
                break;
        }

        $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);

        return view('livewire.admin.service-index', [
            'services' => $query->paginate(10),
            'headers' => $this->getHeaders(),
        ])->layout('layouts.admin');
    }

    public function getHeaders(): array
    {
        return [
            ['key' => 'service_code', 'label' => 'Kode', 'class' => 'w-32 font-bold'],
            ['key' => 'customer_name', 'label' => 'Pelanggan'],
            ['key' => 'category.name', 'label' => 'Kategori'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'created_at', 'label' => 'Tanggal'],
            ['key' => 'technician.name', 'label' => 'Teknisi', 'sortable' => false],
        ];
    }
}
