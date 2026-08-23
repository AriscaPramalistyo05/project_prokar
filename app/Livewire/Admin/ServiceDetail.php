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
        $this->photo_type = in_array($serviceOrder->status, ['in_progress', 'completed']) ? 'after' : 'before';
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
        $this->serviceOrder->refresh();
        $this->logStatusChange('Pesanan disetujui oleh Admin. Menunggu penugasan teknisi.');
        $this->success('Pesanan berhasil disetujui.');
    }

    public function openAssignModal()
    {
        $firstTechId = User::role('teknisi')->first()?->id;
        $this->new_technician_id = $this->serviceOrder->technician_id ?? $firstTechId;
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
        $this->serviceOrder->refresh();
        
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
        $this->serviceOrder->refresh();
        $this->logStatusChange('Pelanggan menyetujui estimasi harga. Lanjut perbaikan.');
        $this->success('Status diubah: Lanjut Perbaikan.');
    }

    public function rejectEstimate()
    {
        $this->serviceOrder->update([
            'status' => 'cancelled',
            'customer_approval' => 'rejected',
        ]);
        $this->serviceOrder->refresh();
        $this->logStatusChange('Pelanggan menolak estimasi harga. Servis dibatalkan.');
        $this->success('Servis dibatalkan.');
    }

    public function cancelService()
    {
        $this->serviceOrder->update(['status' => 'cancelled']);
        $this->serviceOrder->refresh();
        $this->logStatusChange('Servis dibatalkan oleh Admin.');
        $this->success('Servis berhasil dibatalkan.');
    }

    // ─── ALUR TEKNISI ───

    public function startDiagnosing()
    {
        $this->serviceOrder->update(['status' => 'diagnosing']);
        $this->serviceOrder->refresh();
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
        $this->new_estimated_cost = (float) preg_replace('/[^0-9.]/', '', (string) $this->new_estimated_cost);

        $this->validate([
            'new_diagnosis' => 'required|string',
            'new_estimated_cost' => 'required|numeric|min:0'
        ], [
            'new_diagnosis.required' => 'Hasil diagnosa wajib diisi.',
            'new_estimated_cost.required' => 'Estimasi biaya wajib diisi.',
            'new_estimated_cost.numeric' => 'Estimasi biaya harus berupa angka.',
        ]);

        $this->serviceOrder->update([
            'diagnosis' => $this->new_diagnosis,
            'estimated_cost' => $this->new_estimated_cost,
            'status' => 'waiting_approval'
        ]);
        $this->serviceOrder->refresh();
        
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
        $this->new_final_cost = (float) preg_replace('/[^0-9.]/', '', (string) $this->new_final_cost);

        $this->validate([
            'new_final_cost' => 'required|numeric|min:0'
        ], [
            'new_final_cost.required' => 'Biaya final wajib diisi.',
            'new_final_cost.numeric' => 'Biaya final harus berupa angka.',
        ]);

        $warrantyDays = (int) setting('warranty_duration_days', 30);
        
        $updates = [
            'final_cost' => $this->new_final_cost,
            'status' => 'completed',
            'completed_at' => now(),
            'warranty_until' => now()->addDays($warrantyDays),
        ];

        if ($this->serviceOrder->service_type === 'home_visit') {
            $updates['payment_status'] = 'paid';
            $updates['paid_at'] = now();
            $logNote = 'Pekerjaan selesai dan pembayaran lunas diterima oleh teknisi di lokasi.';
        } else {
            $updates['payment_status'] = 'unpaid';
            $logNote = 'Pekerjaan selesai. Menunggu pengambilan dan pembayaran di toko.';
        }

        $this->serviceOrder->update($updates);
        $this->serviceOrder->refresh();

        $this->logStatusChange($logNote);
        $this->final_modal = false;
        $this->success('Servis berhasil diselesaikan!');
    }

    public function markAsPaid(string $method = 'cash')
    {
        $methodLabel = match($method) {
            'transfer' => 'Transfer Bank',
            'qris' => 'QRIS Toko',
            default => 'Tunai (Cash)',
        };

        $this->serviceOrder->update([
            'payment_status' => 'paid',
            'payment_method' => $method,
            'paid_at' => now(),
        ]);
        $this->serviceOrder->refresh();
        
        $this->logStatusChange("Pembayaran lunas ({$methodLabel}) diterima oleh Admin/Kasir.");
        $this->success("Status pembayaran diubah: Lunas via {$methodLabel}.");
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
        $this->fee_amount = (float) preg_replace('/[^0-9.]/', '', (string) $this->fee_amount);

        $this->validate([
            'fee_name' => 'required|string',
            'fee_amount' => 'required|numeric|min:1'
        ], [
            'fee_name.required' => 'Nama biaya wajib diisi.',
            'fee_amount.required' => 'Nominal biaya wajib diisi.',
            'fee_amount.numeric' => 'Nominal biaya harus berupa angka.',
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

    private function compressAndStoreImage($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $imageExtensions = ['jpeg', 'jpg', 'png', 'webp'];

        if (!in_array($extension, $imageExtensions)) {
            return $file->store('service_images', 'public');
        }

        $filename = 'service_images/' . \Illuminate\Support\Str::random(40) . '.webp';
        $fullPath = storage_path('app/public/' . $filename);

        if (!file_exists(storage_path('app/public/service_images'))) {
            mkdir(storage_path('app/public/service_images'), 0755, true);
        }

        try {
            $sourcePath = $file->getRealPath();
            list($width, $height) = getimagesize($sourcePath);

            $maxDim = 1600;
            if ($width > $maxDim || $height > $maxDim) {
                $ratio = min($maxDim / $width, $maxDim / $height);
                $newWidth = (int) ($width * $ratio);
                $newHeight = (int) ($height * $ratio);
            } else {
                $newWidth = $width;
                $newHeight = $height;
            }

            $srcImage = match($extension) {
                'png' => imagecreatefrompng($sourcePath),
                'webp' => imagecreatefromwebp($sourcePath),
                default => imagecreatefromjpeg($sourcePath),
            };

            if ($srcImage) {
                $dstImage = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($dstImage, false);
                imagesavealpha($dstImage, true);

                imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagewebp($dstImage, $fullPath, 75);

                imagedestroy($srcImage);
                imagedestroy($dstImage);

                return $filename;
            }
        } catch (\Throwable $e) {
            // Fallback to standard store if GD fails
        }

        return $file->store('service_images', 'public');
    }

    public function uploadPhoto()
    {
        $this->validate([
            'new_photo' => 'required|file|mimes:jpeg,png,jpg,webp,mp4,mov,avi,webm|max:20480',
            'photo_type' => 'required|in:before,after',
        ]);

        $path = $this->compressAndStoreImage($this->new_photo);

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
        $this->serviceOrder->refresh();
        
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
