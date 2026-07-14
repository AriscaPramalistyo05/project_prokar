<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class TrackingSearch extends Component
{
    public string $ticketNumber = '';
    public string $activeState = 'ongoing';
    public ?string $errorMessage = null;

    protected $queryString = ['code' => ['as' => 'code', 'except' => '']];
    public $code = '';
    
    public $userServices = [];

    public function mount()
    {
        if ($this->code) {
            $this->ticketNumber = $this->code;
            $this->search();
        }
    }

    public function search(): void
    {
        $val = strtoupper(trim($this->ticketNumber));

        if (empty($val)) {
            $this->errorMessage = 'Silakan masukkan kode servis.';
            $this->dispatch('tracking-updated', state: null, ticket: $val);
            return;
        }

        $order = \App\Models\ServiceOrder::where('service_code', $val)->first();

        if ($order) {
            return redirect()->route('servis.track', ['code' => $val]);
        } else {
            $this->errorMessage = 'Nomor tiket tidak ditemukan. Pastikan kode sudah benar (contoh: SRV-2026...).';
        }
    }

    #[Livewire\Attributes\On('sync-local-codes')]
    public function syncLocalCodes($codes)
    {
        if (\Illuminate\Support\Facades\Auth::check() && is_array($codes) && count($codes) > 0) {
            $userId = \Illuminate\Support\Facades\Auth::id();
            
            // Sync all codes that belong to this session but don't have a user_id yet
            \App\Models\ServiceOrder::whereIn('service_code', $codes)
                ->whereNull('user_id')
                ->update(['user_id' => $userId]);
        }
    }

    public function switchState(string $state): void
    {
        $this->activeState = $state;
        $this->errorMessage = null;
        $this->dispatch('tracking-updated', state: $state, ticket: $this->ticketNumber);
    }

    public function resetSearch(): void
    {
        $this->activeState = 'ongoing';
        $this->errorMessage = null;
    }

    public function render()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $this->userServices = \App\Models\ServiceOrder::where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->latest()
                ->get()
                ->toArray();
        } else {
            $this->userServices = []; // Will be populated by JS from localStorage
        }

        return view('livewire.frontend.tracking-search');
    }
}
