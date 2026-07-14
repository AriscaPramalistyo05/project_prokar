<?php

namespace App\Livewire\Frontend;

use App\Models\ServiceOrder;
use Livewire\Component;

class TrackService extends Component
{
    public ServiceOrder $serviceOrder;

    public function mount($code)
    {
        $this->serviceOrder = ServiceOrder::where('service_code', $code)
            ->with(['serviceStatusLogs' => function ($query) {
                $query->latest();
            }])
            ->firstOrFail();
    }

    public function approveCost()
    {
        if ($this->serviceOrder->status === 'waiting_approval') {
            $this->serviceOrder->update([
                'status' => 'in_progress',
            ]);
            
            // Refresh model to get updated logs
            $this->serviceOrder->refresh();
        }
    }

    public function rejectCost()
    {
        if ($this->serviceOrder->status === 'waiting_approval') {
            $this->serviceOrder->update([
                'status' => 'cancelled',
            ]);
            
            // Refresh model
            $this->serviceOrder->refresh();
        }
    }

    public function render()
    {
        return view('livewire.frontend.track-service')
            ->layout('layouts.app');
    }
}
