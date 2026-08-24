<?php

namespace App\Livewire\Frontend;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class UserSettings extends Component
{
    public string $selectedTab = 'security';

    // Password Update Fields
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Feedback Messages
    public ?string $passwordSuccess = null;
    public ?string $errorMessage = null;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user) {
            $this->redirectRoute('login');
            return;
        }

        if (request()->has('tab')) {
            $tab = request()->query('tab');
            if (in_array($tab, ['security', 'preferences'])) {
                $this->selectedTab = $tab;
            }
        }
    }

    public function setTab(string $tab): void
    {
        if (in_array($tab, ['security', 'preferences'])) {
            $this->selectedTab = $tab;
            $this->passwordSuccess = null;
            $this->errorMessage = null;
        }
    }

    public function updatePassword(): void
    {
        $user = Auth::user();

        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok dengan catatan kami.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->passwordSuccess = 'Kata sandi Anda berhasil diperbarui!';
    }

    public function render()
    {
        return view('livewire.frontend.user-settings', [
            'user' => Auth::user(),
        ]);
    }
}
