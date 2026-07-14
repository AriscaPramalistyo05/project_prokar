<?php

namespace App\Livewire\Admin;

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

    // Form fields
    public $status;
    public $diagnosis;
    public $estimated_cost;
    public $final_cost;
    public $notes;
    public $technician_id;

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
        
        $this->status = $serviceOrder->status;
        $this->diagnosis = $serviceOrder->diagnosis;
        $this->estimated_cost = $serviceOrder->estimated_cost;
        $this->final_cost = $serviceOrder->final_cost;
        $this->notes = $serviceOrder->notes;
        $this->technician_id = $serviceOrder->technician_id;
    }

    public function rules()
    {
        return [
            'status' => 'required|in:pending,confirmed,diagnosing,waiting_approval,in_progress,completed,cancelled',
            'diagnosis' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0',
            'final_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'technician_id' => 'nullable|exists:users,id',
        ];
    }

    public function updateService()
    {
        $this->validate();

        // If completed just now
        if ($this->status === 'completed' && $this->serviceOrder->status !== 'completed') {
            $this->serviceOrder->completed_at = now();
            // Automatically add warranty days from settings (default 30 days if setting not found)
            $warrantyDays = (int) setting('warranty_duration_days', 30);
            $this->serviceOrder->warranty_until = now()->addDays($warrantyDays);
        }

        $this->serviceOrder->update([
            'status' => $this->status,
            'diagnosis' => $this->diagnosis,
            'estimated_cost' => $this->estimated_cost,
            'final_cost' => $this->final_cost,
            'notes' => $this->notes,
            'technician_id' => $this->technician_id,
        ]);

        $this->success('Data servis berhasil diperbarui!');
    }

    public function uploadPhoto()
    {
        $this->validate([
            'new_photo' => 'required|image|max:10240',
            'photo_type' => 'required|in:before,after',
        ]);

        $path = $this->new_photo->store('service_images', 'public');

        $this->serviceOrder->serviceImages()->create([
            'path' => $path,
            'type' => $this->photo_type,
            'uploaded_by' => Auth::id(),
            'media_type' => 'image',
        ]);

        $this->reset('new_photo');
        $this->success('Foto berhasil diunggah!');
    }

    public function render()
    {
        $technicians = User::role('teknisi')->get();

        return view('livewire.admin.service-detail', [
            'technicians' => $technicians,
            'statusLogs' => $this->serviceOrder->serviceStatusLogs()->latest()->get(),
            'images' => $this->serviceOrder->serviceImages()->latest()->get(),
        ]);
    }
}
