<?php

namespace App\Livewire\Frontend;

use App\Models\ServiceOrder;
use Livewire\Component;

class TrackService extends Component
{
    public ServiceOrder $serviceOrder;
    public string $newTicketCode = '';

    public function mount($code)
    {
        $this->newTicketCode = $code; // Populate input with current code
        $this->serviceOrder = ServiceOrder::where('service_code', $code)
            ->with(['serviceStatusLogs' => function ($query) {
                $query->latest();
            }])
            ->firstOrFail();
    }

    public function searchTicket()
    {
        $val = strtoupper(trim($this->newTicketCode));
        if (!empty($val)) {
            return redirect()->route('servis.track', ['code' => $val]);
        }
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

    public function getLogsProperty()
    {
        $logs = $this->serviceOrder->serviceStatusLogs->sortBy('created_at')->values();
        
        // 1. Ensure 'pending' exists at the start
        if (!$logs->contains('status', 'pending')) {
            $pendingLog = new \App\Models\ServiceStatusLog([
                'status' => 'pending',
                'note' => 'Pengajuan servis berhasil diterima.',
            ]);
            $pendingLog->created_at = $this->serviceOrder->created_at;
            $logs->prepend($pendingLog);
        }

        // 2. If the current status of the order is NOT the last log's status, inject a synthetic log
        // This handles cases where status was updated before we added the logging mechanism.
        if ($logs->last()->status !== $this->serviceOrder->status) {
             $currentLog = new \App\Models\ServiceStatusLog([
                 'status' => $this->serviceOrder->status,
                 'note' => 'Status saat ini (Sistem).',
             ]);
             $currentLog->created_at = $this->serviceOrder->updated_at;
             $logs->push($currentLog);
        }

        return $logs;
    }


    public function render()
    {
        return view('livewire.frontend.track-service', [
            'logs' => $this->logs,
        ])->layout('layouts.app');
    }
}
