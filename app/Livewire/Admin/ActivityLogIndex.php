<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Layout('layouts.admin')]
class ActivityLogIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $filterUser = '';

    #[Url]
    public string $filterModel = '';

    #[Url]
    public string $filterEvent = '';

    #[Url]
    public string $startDate = '';

    #[Url]
    public string $endDate = '';

    public bool $showDetailModal = false;
    public ?Activity $selectedActivity = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterUser' => ['except' => ''],
        'filterModel' => ['except' => ''],
        'filterEvent' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function mount()
    {
        // Default to last 30 days if empty
        if (empty($this->startDate)) {
            $this->startDate = Carbon::now()->subDays(30)->toDateString();
        }
        if (empty($this->endDate)) {
            $this->endDate = Carbon::now()->toDateString();
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterUser()
    {
        $this->resetPage();
    }

    public function updatedFilterModel()
    {
        $this->resetPage();
    }

    public function updatedFilterEvent()
    {
        $this->resetPage();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterUser = '';
        $this->filterModel = '';
        $this->filterEvent = '';
        $this->startDate = Carbon::now()->subDays(30)->toDateString();
        $this->endDate = Carbon::now()->toDateString();
        $this->resetPage();
    }

    public function showDetail(int $activityId)
    {
        $this->selectedActivity = Activity::with('causer')->find($activityId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedActivity = null;
    }

    public function render()
    {
        // Only fetch official staff members (Super Admin, Admin, Teknisi)
        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['super_admin', 'admin', 'teknisi']);
        })->orderBy('name')->get();

        $models = [
            'App\Models\Order' => 'Order / Penjualan',
            'App\Models\Product' => 'Produk',
            'App\Models\ServiceOrder' => 'Servis Elektronik',
            'App\Models\SellSubmission' => 'Jual / Barang Masuk',
            'App\Models\User' => 'Pengguna (User)',
            'App\Models\Category' => 'Kategori',
            'App\Models\Setting' => 'Pengaturan',
        ];

        $events = [
            'created' => 'Created (Dibuat)',
            'updated' => 'Updated (Diubah)',
            'deleted' => 'Deleted (Dihapus)',
        ];

        $activities = Activity::with('causer')
            ->whereHas('causer', function ($u) {
                $u->whereHas('roles', function ($r) {
                    $r->whereIn('name', ['super_admin', 'admin', 'teknisi']);
                });
            })
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('description', 'like', '%' . $this->search . '%')
                        ->orWhere('log_name', 'like', '%' . $this->search . '%')
                        ->orWhere('properties', 'like', '%' . $this->search . '%')
                        ->orWhereHas('causer', function ($u) {
                            $u->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterUser, function ($q) {
                $q->where('causer_id', $this->filterUser);
            })
            ->when($this->filterModel, function ($q) {
                $q->where('subject_type', $this->filterModel);
            })
            ->when($this->filterEvent, function ($q) {
                $q->where('event', $this->filterEvent);
            })
            ->when($this->startDate && $this->endDate, function ($q) {
                $q->whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay(),
                ]);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.activity-log-index', [
            'activities' => $activities,
            'users' => $users,
            'models' => $models,
            'events' => $events,
        ]);
    }
}
