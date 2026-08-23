<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin')]
class UserForm extends Component
{
    use Toast;

    public ?User $user = null;
    public bool $isEdit = false;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $selectedRole = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $is_suspended = false;

    public function mount(?User $user = null): void
    {
        if ($user && $user->exists) {
            $this->user = $user;
            $this->isEdit = true;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone ?? '';
            $this->is_suspended = (bool) $user->is_suspended;
            $this->selectedRole = $user->roles->first()?->name ?? '';
        }
    }

    public function generatePassword(): void
    {
        $generated = 'Prokar' . rand(1000, 9999) . '!' . Str::random(2);
        $this->password = $generated;
        $this->password_confirmation = $generated;
        $this->info('Password Dibuat', 'Password acak telah diisikan ke form.');
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                $this->isEdit ? Rule::unique('users')->ignore($this->user->id) : 'unique:users,email',
            ],
            'phone' => 'nullable|string|max:25',
            'selectedRole' => 'nullable|string|exists:roles,name',
            'is_suspended' => 'boolean',
        ];

        if (!$this->isEdit) {
            $rules['password'] = 'required|string|min:8|same:password_confirmation';
        } else {
            $rules['password'] = 'nullable|string|min:8|same:password_confirmation';
        }

        $this->validate($rules, [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.same' => 'Konfirmasi password tidak cocok.',
        ]);

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'is_suspended' => $this->is_suspended,
        ];

        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->isEdit) {
            $this->user->update($userData);
            $targetUser = $this->user;
        } else {
            $userData['email_verified_at'] = now();
            $targetUser = User::create($userData);
        }

        // Assign Spatie Role
        if (!empty($this->selectedRole)) {
            $targetUser->syncRoles([$this->selectedRole]);
        } else {
            $targetUser->syncRoles([]);
        }

        $msg = $this->isEdit ? "Data pengguna {$targetUser->name} berhasil diperbarui." : "Pengguna baru {$targetUser->name} berhasil ditambahkan.";
        $this->success('Berhasil', $msg);

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        $roles = Role::orderBy('name')->get();

        $roleOptions = $roles->map(function ($role) {
            return [
                'id' => $role->name,
                'name' => ucwords(str_replace('_', ' ', $role->name)),
            ];
        })->prepend([
            'id' => '',
            'name' => '-- Tanpa Role Khusus (Pelanggan) --',
        ]);

        return view('livewire.admin.user-form', [
            'roleOptions' => $roleOptions,
        ]);
    }
}
