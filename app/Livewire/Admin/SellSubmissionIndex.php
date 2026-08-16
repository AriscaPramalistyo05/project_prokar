<?php

namespace App\Livewire\Admin;

use App\Models\SellSubmission;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class SellSubmissionIndex extends Component
{
    use WithPagination;

    #[Layout('layouts.admin')]
    public string $search = '';
    public string $statusFilter = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $submissions = SellSubmission::query()
            ->with('category')
            ->when($this->search, function ($query) {
                $query->where('submission_code', 'like', '%' . $this->search . '%')
                      ->orWhere('customer_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.sell-submission-index', [
            'submissions' => $submissions
        ]);
    }
}
