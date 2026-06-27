<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class TrackingResult extends Component
{
    public ?string $state = null;
    public ?string $ticket = null;

    protected $listeners = [
        'tracking-updated' => 'updateFromSearch',
    ];

    public function mount(): void
    {
        // Default: show "ongoing" ticket on first render
        $this->state = 'ongoing';
        $this->ticket = 'TRX-SERVIS-001';
    }

    public function updateFromSearch(?string $state, ?string $ticket): void
    {
        $this->state = $state;
        $this->ticket = $ticket;
    }

    public function render()
    {
        return view('livewire.frontend.tracking-result');
    }
}
