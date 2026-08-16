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

    public function mount()
    {
        if ($this->code) {
            $this->ticketNumber = $this->code;
            $this->search();
        }
    }

    public function search()
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
        return view('livewire.frontend.tracking-search');
    }
}
