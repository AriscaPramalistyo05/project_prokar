<?php

namespace App\Livewire\Frontend;

use App\Models\Order;
use App\Models\SellSubmission;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class UserProfile extends Component
{
    use WithFileUploads;

    public string $selectedTab = 'orders';

    // Biodata Form Fields
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public $avatar_file = null;
    public ?string $existing_avatar = null;

    // Feedback message
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    // Selected order for detail modal
    public ?int $selectedOrderId = null;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user) {
            $this->redirectRoute('login');
            return;
        }

        $this->name = (string) $user->name;
        $this->email = (string) $user->email;
        $this->phone = (string) ($user->phone ?? '');
        $this->existing_avatar = $user->avatar;

        if (request()->has('tab')) {
            $tab = request()->query('tab');
            if (in_array($tab, ['orders', 'services', 'sells', 'profile'])) {
                $this->selectedTab = $tab;
            }
        }
    }

    public function setTab(string $tab): void
    {
        if (in_array($tab, ['orders', 'services', 'sells', 'profile'])) {
            $this->selectedTab = $tab;
            $this->successMessage = null;
            $this->errorMessage = null;
        }
    }

    public function refreshData(): void
    {
    }

    public function saveProfile(): void
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:100|min:3',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar_file' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'avatar_file.image' => 'Foto profil harus berupa file gambar.',
            'avatar_file.max' => 'Ukuran foto profil maksimal 2MB.',
        ]);

        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];

        if ($this->avatar_file) {
            // Delete old custom avatar if stored
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $this->avatar_file->store('avatars', 'public');
            $updateData['avatar'] = $path;
            $this->existing_avatar = $path;
            $this->avatar_file = null;
        }

        $user->update($updateData);

        $this->successMessage = 'Profil Anda berhasil diperbarui!';
        $this->dispatch('profile-updated');
    }

    public function approveServiceCost(int $serviceId): void
    {
        $user = Auth::user();
        $service = ServiceOrder::where('id', $serviceId)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
            })->first();

        if ($service && $service->status === 'waiting_approval') {
            $service->update([
                'customer_approval' => 'approved',
                'approved_at' => now(),
                'status' => 'in_progress',
            ]);
            $this->successMessage = "Estimasi biaya servis #{$service->service_code} berhasil disetujui. Teknisi kami akan segera memproses pengerjaan.";
        }
    }

    public function rejectServiceCost(int $serviceId): void
    {
        $user = Auth::user();
        $service = ServiceOrder::where('id', $serviceId)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
            })->first();

        if ($service && $service->status === 'waiting_approval') {
            $service->update([
                'customer_approval' => 'rejected',
                'status' => 'cancelled',
            ]);
            $this->successMessage = "Estimasi biaya servis #{$service->service_code} telah ditolak.";
        }
    }

    public function render()
    {
        $user = Auth::user();

        // 1. Orders
        $orders = Order::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('customer_email', $user->email);
            if (!empty($user->phone)) {
                $q->orWhere('customer_phone', $user->phone);
            }
        })->with(['orderItems.product'])->latest()->get();

        // 2. Service Orders
        $services = ServiceOrder::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('customer_email', $user->email);
            if (!empty($user->phone)) {
                $q->orWhere('customer_phone', $user->phone);
            }
        })->with(['technician', 'category'])->latest()->get();

        // 3. Sell Submissions
        $sells = SellSubmission::where(function ($q) use ($user) {
            if (!empty($user->phone)) {
                $q->where('customer_whatsapp', $user->phone)
                  ->orWhere('customer_phone', $user->phone);
            } else {
                $q->where('customer_name', $user->name);
            }
        })->with(['category'])->latest()->get();

        return view('livewire.frontend.user-profile', [
            'user' => $user,
            'orders' => $orders,
            'services' => $services,
            'sells' => $sells,
        ]);
    }
}
