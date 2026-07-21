<?php

namespace App\Livewire\Admin;

use App\Models\AdditionalFee;
use App\Models\ServiceFee;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class ServiceDetail extends Component
{
    use Toast, WithFileUploads;

    public ServiceOrder $serviceOrder;

    // Modals
    public bool $assign_modal = false;
    public bool $diagnose_modal = false;
    public bool $final_modal = false;
    public bool $fee_modal = false;

    // Form Fields
    public $new_technician_id = null;
    public $new_diagnosis = '';
    public $new_estimated_cost = 0;
    public $new_final_cost = 0;
    
    // Extra Fee Fields
    public $selected_fee_id = null;
    public $fee_name = '';
    public $fee_amount = 0;

    // Upload fields
    public $photo_type = 'before';
    public $new_photo;

    public function mount(ServiceOrder $serviceOrder)
    {
        // Check authorization
        if (Auth::user()->hasRole('teknisi') && $serviceOrder->technician_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan servis ini.');
        }

        $this->serviceOrder = $serviceOrder;
    }

    private function logStatusChange(string $note)
    {
        $this->serviceOrder->serviceStatusLogs()->create([
            'status' => $this->serviceOrder->status,
            'note' => $note,
            'changed_by' => Auth::id(),
        ]);
    }

    // ─── ALUR ADMIN ───

    public function acceptService()
    {
        // Jika home_visit -> langsung confirmed. Jika drop_off -> menunggu barang
        $newStatus = $this->serviceOrder->service_type === 'home_visit' ? 'confirmed' : 'pending'; // or a new status
        // Wait, current DB statuses are: pending, confirmed, diagnosing, waiting_approval, in_progress, completed, cancelled
        // Let's use 'confirmed' for home visit, and maybe keep it 'pending' but add a note for drop off.
        // Or just set to 'confirmed' and if it's drop off, admin still needs to assign technician.
        // Let's just set to 'confirmed' for both, but for drop off it means "Menunggu Barang & Teknisi".
        
        $this->serviceOrder->update(['status' => 'confirmed']);
        $this->logStatusChange('Pesanan disetujui oleh Admin. Menunggu penugasan teknisi.');
        $this->success('Pesanan berhasil disetujui.');
    }

    public function openAssignModal()
    {
        $this->new_technician_id = $this->serviceOrder->technician_id;
        $this->assign_modal = true;
    }

    public function assignTechnician()
    {
        $this->validate([
            'new_technician_id' => 'required|exists:users,id'
        ], ['new_technician_id.required' => 'Pilih teknisi terlebih dahulu.']);

        $this->serviceOrder->update([
            'technician_id' => $this->new_technician_id,
            'status' => 'confirmed' // Ensures it stays or moves to confirmed
        ]);
        
        $tech = User::find($this->new_technician_id);
        $this->logStatusChange('Ditugaskan kepada teknisi: ' . $tech->name);
        
        $this->assign_modal = false;
        $this->success('Teknisi berhasil ditugaskan.');
    }

    public function approveEstimate()
    {
        $this->serviceOrder->update([
            'status' => 'in_progress',
            'customer_approval' => 'approved',
            'approved_at' => now(),
        ]);
        $this->logStatusChange('Pelanggan menyetujui estimasi harga. Lanjut perbaikan.');
        $this->success('Status diubah: Lanjut Perbaikan.');
    }

    public function rejectEstimate()
    {
        $this->serviceOrder->update([
            'status' => 'cancelled',
            'customer_approval' => 'rejected',
        ]);
        $this->logStatusChange('Pelanggan menolak estimasi harga. Servis dibatalkan.');
        $this->success('Servis dibatalkan.');
    }

    public function cancelService()
    {
        $this->serviceOrder->update(['status' => 'cancelled']);
        $this->logStatusChange('Servis dibatalkan oleh Admin.');
        $this->success('Servis berhasil dibatalkan.');
    }

    // ─── ALUR TEKNISI ───

    public function startDiagnosing()
    {
        $this->serviceOrder->update(['status' => 'diagnosing']);
        $this->logStatusChange('Teknisi mulai melakukan pengecekan.');
        $this->success('Pengecekan dimulai.');
    }

    public function openDiagnoseModal()
    {
        $this->new_diagnosis = $this->serviceOrder->diagnosis;
        $this->new_estimated_cost = $this->serviceOrder->estimated_cost;
        $this->diagnose_modal = true;
    }

    public function submitEstimate()
    {
        $this->validate([
            'new_diagnosis' => 'required|string',
            'new_estimated_cost' => 'required|numeric|min:0'
        ]);

        $this->serviceOrder->update([
            'diagnosis' => $this->new_diagnosis,
            'estimated_cost' => $this->new_estimated_cost,
            'status' => 'waiting_approval'
        ]);
        
        $this->logStatusChange('Teknisi mengirimkan hasil diagnosa dan estimasi harga.');
        $this->diagnose_modal = false;
        $this->success('Estimasi berhasil dikirim ke Admin.');
    }

    public function openFinalModal()
    {
        // Pre-fill with estimated cost if final cost is 0
        $this->new_final_cost = $this->serviceOrder->final_cost > 0 ? $this->serviceOrder->final_cost : $this->serviceOrder->estimated_cost;
        $this->final_modal = true;
    }

    public function completeService()
    {
        $this->validate([
            'new_final_cost' => 'required|numeric|min:0'
        ]);

        $warrantyDays = (int) setting('warranty_duration_days', 30);
        
        $this->serviceOrder->update([
            'final_cost' => $this->new_final_cost,
            'status' => 'completed',
            'completed_at' => now(),
            'warranty_until' => now()->addDays($warrantyDays),
        ]);

        $this->logStatusChange('Pekerjaan selesai. Menunggu pengambilan/pengiriman.');
        $this->final_modal = false;
        $this->success('Servis berhasil diselesaikan!');
    }

    // ─── BIAYA TAMBAHAN ───

    public function openFeeModal()
    {
        $this->reset(['selected_fee_id', 'fee_name', 'fee_amount']);
        $this->fee_modal = true;
    }

    public function updatedSelectedFeeId($value)
    {
        if ($value) {
            $fee = AdditionalFee::find($value);
            if ($fee) {
                $this->fee_name = $fee->name;
                $this->fee_amount = $fee->default_amount;
            }
        }
    }

    public function addExtraFee()
    {
        $this->validate([
            'fee_name' => 'required|string',
            'fee_amount' => 'required|numeric|min:1'
        ]);

        $this->serviceOrder->serviceFees()->create([
            'fee_name' => $this->fee_name,
            'amount' => $this->fee_amount
        ]);

        $this->fee_modal = false;
        $this->success('Biaya tambahan berhasil ditambahkan.');
    }

    public function removeExtraFee($id)
    {
        $this->serviceOrder->serviceFees()->where('id', $id)->delete();
        $this->success('Biaya tambahan dihapus.');
    }

    // ─── FOTO ───

    public function uploadPhoto()
    {
        $this->validate([
            'new_photo' => 'required|file|mimes:jpeg,png,jpg,webp,mp4,mov,avi,webm|max:20480',
            'photo_type' => 'required|in:before,after',
        ]);

        $path = $this->new_photo->store('service_images', 'public');

        $extension = strtolower($this->new_photo->getClientOriginalExtension());
        $videoExts = ['mp4', 'mov', 'avi', 'webm'];
        $mediaType = in_array($extension, $videoExts) ? 'video' : 'image';

        $this->serviceOrder->serviceImages()->create([
            'path' => $path,
            'type' => $this->photo_type,
            'uploaded_by' => Auth::id(),
            'media_type' => $mediaType,
        ]);

        $this->reset('new_photo');
        $this->success('Foto/Video berhasil diunggah!');
    }

    public function render()
    {
        $technicians = User::role('teknisi')->get();
        $masterFees = AdditionalFee::where('is_active', true)->get();

        return view('livewire.admin.service-detail', [
            'technicians' => $technicians,
            'masterFees' => $masterFees,
            'statusLogs' => $this->serviceOrder->serviceStatusLogs()->latest()->get(),
            'images' => $this->serviceOrder->serviceImages()->latest()->get(),
            'extraFees' => $this->serviceOrder->serviceFees()->get(),
        ])->layout('layouts.admin');
    }
}
