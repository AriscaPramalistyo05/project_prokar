<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class TrackingSearch extends Component
{
    public string $ticketNumber = 'TRX-SERVIS-001';
    public string $activeState = 'ongoing';
    public ?string $errorMessage = null;

    /**
     * Demo dataset (id => state). In production this would be an API call.
     */
    protected array $validTickets = [
        'TRX-SERVIS-001' => 'ongoing',
        'TRX-SERVIS-002' => 'done',
    ];

    protected $listeners = [
        'reset-tracking' => 'resetSearch',
    ];

    public function search(): void
    {
        $val = strtoupper(trim($this->ticketNumber));

        if (array_key_exists($val, $this->validTickets)) {
            $this->activeState = $this->validTickets[$val];
            $this->errorMessage = null;
            $this->dispatch('tracking-updated', state: $this->activeState, ticket: $val);
        } else {
            $this->errorMessage = 'Nomor tiket tidak ditemukan. Coba: TRX-SERVIS-001 atau TRX-SERVIS-002';
            $this->dispatch('tracking-updated', state: null, ticket: $val);
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
