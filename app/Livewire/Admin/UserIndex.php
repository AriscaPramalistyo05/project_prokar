<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin')]
class UserIndex extends Component
{
    use WithPagination, Toast;

    #[Url]
    public string $search = '';

    #[Url]
    public string $filterRole = '';

    #[Url]
    public string $filterStatus = '';

    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    // Modal Delete
    public bool $showDeleteModal = false;
    public ?int $deleteUserId = null;
    public string $deleteUserName = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterRole(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterRole', 'filterStatus']);
        $this->resetPage();
    }

    public function toggleSuspend(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            $this->error('Gagal', 'Anda tidak dapat menonaktifkan akun Anda sendiri!');
            return;
        }

        $user->update([
            'is_suspended' => !$user->is_suspended,
        ]);

        $statusText = $user->is_suspended ? 'dinonaktifkan (disuspend)' : 'diaktifkan kembali';
        $this->success('Berhasil', "Akun {$user->name} berhasil {$statusText}.");
    }

    public function confirmDelete(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            $this->error('Gagal', 'Anda tidak dapat menghapus akun Anda sendiri!');
            return;
        }

        $this->deleteUserId = $user->id;
        $this->deleteUserName = $user->name;
        $this->showDeleteModal = true;
    }

    public function deleteUser(): void
    {
        if (!$this->deleteUserId) {
            return;
        }

        $user = User::findOrFail($this->deleteUserId);

        if ($user->id === Auth::id()) {
            $this->error('Gagal', 'Anda tidak dapat menghapus akun Anda sendiri!');
            $this->showDeleteModal = false;
            return;
        }

        $user->delete();

        $this->showDeleteModal = false;
        $this->deleteUserId = null;
        $this->deleteUserName = '';
        $this->success('Berhasil Dihapus', "Pengguna {$user->name} berhasil dihapus dari sistem.");
    }

    public function render()
    {
        $roles = Role::orderBy('name')->get();

        $users = User::with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->filterRole);
                });
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_suspended', $this->filterStatus === 'suspended');
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate(15);

        $headers = [
            ['key' => 'cell_user', 'label' => 'Pengguna', 'sortable' => false],
            ['key' => 'phone', 'label' => 'No. Telepon / WA', 'sortable' => false],
            ['key' => 'cell_role', 'label' => 'Peran (Role)', 'sortable' => false],
            ['key' => 'cell_status', 'label' => 'Status Akun', 'sortable' => false],
            ['key' => 'cell_created_at', 'label' => 'Terdaftar', 'sortable' => false],
            ['key' => 'actions', 'label' => 'Aksi', 'sortable' => false, 'class' => 'text-right'],
        ];

        return view('livewire.admin.user-index', [
            'users' => $users,
            'headers' => $headers,
            'roles' => $roles,
        ]);
    }
}
