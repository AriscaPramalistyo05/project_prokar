<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class OrderIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $filterStatus = '';

    #[Url]
    public string $filterPaymentStatus = '';

    public bool $showDetailModal = false;
    public ?Order $selectedOrder = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterPaymentStatus' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterPaymentStatus()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterPaymentStatus = '';
        $this->resetPage();
    }

    public function showDetail(Order $order)
    {
        $this->selectedOrder = $order->load(['orderItems.product', 'user']);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedOrder = null;
    }

    public function updateStatus(Order $order, string $status)
    {
        $order->update(['status' => $status]);
        $this->dispatch('mary-toast', type: 'success', title: 'Status pesanan berhasil diperbarui');
    }

    public function updatePaymentStatus(Order $order, string $paymentStatus)
    {
        $order->update(['payment_status' => $paymentStatus]);
        if ($paymentStatus === 'paid') {
            $order->update(['paid_at' => now()]);
            if ($order->status === 'pending') {
                $order->update(['status' => 'processing']);
            }
        }
        $this->dispatch('mary-toast', type: 'success', title: 'Status pembayaran berhasil diperbarui');
    }

    public function render()
    {
        $totalCount = Order::count();
        $processingCount = Order::where('status', 'processing')->count();
        $paidCount = Order::where('payment_status', 'paid')->count();
        $unpaidCount = Order::where('payment_status', 'unpaid')->count();

        $query = Order::with(['orderItems.product', 'user'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('order_code', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_email', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->when($this->filterPaymentStatus, function ($q) {
                $q->where('payment_status', $this->filterPaymentStatus);
            })
            ->latest();

        $orders = $query->paginate(15);

        return view('livewire.admin.order-index', [
            'orders' => $orders,
            'stats' => [
                'total' => $totalCount,
                'processing' => $processingCount,
                'paid' => $paidCount,
                'unpaid' => $unpaidCount,
            ]
        ])->layout('layouts.admin');
    }
}