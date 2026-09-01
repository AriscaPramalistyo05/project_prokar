<?php

namespace App\Livewire\Frontend;

use App\Models\ServiceOrder;
use Livewire\Component;

class TrackService extends Component
{
    public ServiceOrder $serviceOrder;
    public string $newTicketCode = '';
    public ?string $errorMessage = null;

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
        if (empty($val)) {
            $this->errorMessage = 'Silakan masukkan nomor tiket.';
            return;
        }

        $order = ServiceOrder::where('service_code', $val)->first();
        if ($order) {
            return redirect()->route('servis.track', ['code' => $val]);
        } else {
            $this->errorMessage = "Nomor tiket {$val} tidak ditemukan. Pastikan kode sudah sesuai.";
        }
    }

    public function approveCost()
    {
        if ($this->serviceOrder->status === 'waiting_approval') {
            $this->serviceOrder->update([
                'status' => 'in_progress',
                'customer_approval' => 'approved',
                'approved_at' => now(),
            ]);
            
            event(new \App\Events\CustomerApprovalUpdated($this->serviceOrder, 'approved'));

            if (!empty($this->serviceOrder->customer_email)) {
                try {
                    \Illuminate\Support\Facades\Mail::to($this->serviceOrder->customer_email)
                        ->send(new \App\Mail\ServiceApprovalConfirmationMail($this->serviceOrder, 'approved'));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("Failed sending service approval email: " . $e->getMessage());
                }
            }

            // Refresh model to get updated logs
            $this->serviceOrder->refresh();
        }
    }

    public function rejectCost()
    {
        if ($this->serviceOrder->status === 'waiting_approval') {
            $this->serviceOrder->update([
                'status' => 'cancelled',
                'customer_approval' => 'rejected',
            ]);
            
            event(new \App\Events\CustomerApprovalUpdated($this->serviceOrder, 'rejected'));

            if (!empty($this->serviceOrder->customer_email)) {
                try {
                    \Illuminate\Support\Facades\Mail::to($this->serviceOrder->customer_email)
                        ->send(new \App\Mail\ServiceApprovalConfirmationMail($this->serviceOrder, 'rejected'));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("Failed sending service rejection email: " . $e->getMessage());
                }
            }

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
