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

    #[Url]
    public string $filterPaymentMethod = '';

    public bool $showDetailModal = false;
    public ?Order $selectedOrder = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterPaymentStatus' => ['except' => ''],
        'filterPaymentMethod' => ['except' => ''],
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

    public function updatedFilterPaymentMethod()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterPaymentStatus = '';
        $this->filterPaymentMethod = '';
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
        $data = ['payment_status' => $paymentStatus];
        if ($paymentStatus === 'paid') {
            $data['paid_at'] = now();
            $data['remaining_payment'] = 0;
            if ($order->status === 'pending') {
                $data['status'] = 'processing';
            }
        } elseif ($paymentStatus === 'dp_paid') {
            $data['paid_at'] = now();
            if ($order->status === 'pending') {
                $data['status'] = 'processing';
            }
        } elseif ($paymentStatus === 'unpaid') {
            $data['paid_at'] = null;
        }

        $order->update($data);

        if ($this->selectedOrder && $this->selectedOrder->id === $order->id) {
            $this->selectedOrder = $order->fresh(['orderItems.product', 'user']);
        }
        $this->dispatch('mary-toast', type: 'success', title: 'Status pembayaran berhasil diperbarui');
    }

    public function verifyStorePayment(Order $order)
    {
        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'remaining_payment' => 0,
            'status' => 'completed',
        ]);

        if ($this->selectedOrder && $this->selectedOrder->id === $order->id) {
            $this->selectedOrder = $order->fresh(['orderItems.product', 'user']);
        }
        $this->dispatch('mary-toast', type: 'success', title: 'Pembayaran tunai di kasir toko berhasil diverifikasi (Pesanan Selesai)');
    }

    public function verifyCodFullPayment(Order $order)
    {
        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'remaining_payment' => 0,
            'status' => 'completed',
        ]);

        if ($this->selectedOrder && $this->selectedOrder->id === $order->id) {
            $this->selectedOrder = $order->fresh(['orderItems.product', 'user']);
        }
        $this->dispatch('mary-toast', type: 'success', title: 'Setoran pembayaran COD berhasil diverifikasi (Pesanan Selesai)');
    }

    public function settleRemainingPayment(Order $order, string $method = 'cash')
    {
        $order->update([
            'payment_status' => 'paid',
            'payment_method' => $method,
            'remaining_payment' => 0,
            'paid_at' => now(),
            'status' => 'completed',
        ]);

        if ($this->selectedOrder && $this->selectedOrder->id === $order->id) {
            $this->selectedOrder = $order->fresh(['orderItems.product', 'user']);
        }
        $this->dispatch('mary-toast', type: 'success', title: 'Pelunasan sisa tagihan COD berhasil dicatat (Pesanan Selesai)');
    }

    public function render()
    {
        $totalCount = Order::count();
        $processingCount = Order::where('status', 'processing')->count();
        $paidCount = Order::where('payment_status', 'paid')->count();
        $dpPaidCount = Order::where('payment_status', 'dp_paid')->count();
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
            ->when($this->filterPaymentMethod, function ($q) {
                if ($this->filterPaymentMethod === 'dp') {
                    $q->where(function ($sub) {
                        $sub->where('payment_type', 'down_payment')
                            ->orWhere('payment_method', 'midtrans_dp');
                    });
                } else {
                    $q->where('payment_method', $this->filterPaymentMethod);
                }
            })
            ->latest();

        $orders = $query->paginate(15);

        return view('livewire.admin.order-index', [
            'orders' => $orders,
            'stats' => [
                'total' => $totalCount,
                'processing' => $processingCount,
                'paid' => $paidCount,
                'dp_paid' => $dpPaidCount,
                'unpaid' => $unpaidCount,
            ]
        ])->layout('layouts.admin');
    }
}