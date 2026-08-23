<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Order;
use App\Models\SellSubmission;
use App\Models\ServiceOrder;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.admin')]
class ReportIndex extends Component
{
    use WithPagination;

    public string $reportType = 'penjualan'; // penjualan, servis, barang_masuk
    public string $startDate = '';
    public string $endDate = '';
    public string $statusFilter = 'all';

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
    }

    public function updatedReportType()
    {
        $this->statusFilter = 'all';
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

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    public function exportExcel(): StreamedResponse
    {
        $typeLabel = match($this->reportType) {
            'penjualan' => 'Penjualan',
            'servis' => 'Servis',
            default => 'Barang_Masuk',
        };
        $filename = "Laporan_{$typeLabel}_{$this->startDate}_sd_{$this->endDate}.xls";

        $data = [
            'type' => $this->reportType,
            'startDate' => Carbon::parse($this->startDate)->translatedFormat('d F Y'),
            'endDate' => Carbon::parse($this->endDate)->translatedFormat('d F Y'),
            'generatedAt' => Carbon::now()->translatedFormat('d F Y H:i'),
        ];

        if ($this->reportType === 'penjualan') {
            $data['items'] = $this->getOrderQuery()->get();
            $data['totalRevenue'] = $data['items']->where('payment_status', 'paid')->sum('total');
            $data['totalOrders'] = $data['items']->count();
        } elseif ($this->reportType === 'servis') {
            $data['items'] = $this->getServiceQuery()->get();
            $data['totalCost'] = $data['items']->where('payment_status', 'paid')->sum('final_cost');
            $data['totalServices'] = $data['items']->count();
        } else {
            $data['items'] = $this->getSellQuery()->get();
            $data['totalAgreed'] = $data['items']->where('status', 'paid')->sum('agreed_price');
            $data['totalSells'] = $data['items']->count();
        }

        return response()->streamDownload(function () use ($data) {
            echo view('exports.report-excel', $data)->render();
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportPdf()
    {
        $data = [
            'type' => $this->reportType,
            'startDate' => Carbon::parse($this->startDate)->translatedFormat('d F Y'),
            'endDate' => Carbon::parse($this->endDate)->translatedFormat('d F Y'),
            'generatedAt' => Carbon::now()->translatedFormat('d F Y H:i'),
            'statusFilter' => $this->statusFilter,
        ];

        if ($this->reportType === 'penjualan') {
            $data['items'] = $this->getOrderQuery()->get();
            $data['totalRevenue'] = $data['items']->where('payment_status', 'paid')->sum('total');
            $data['totalOrders'] = $data['items']->count();
        } elseif ($this->reportType === 'servis') {
            $data['items'] = $this->getServiceQuery()->get();
            $data['totalCost'] = $data['items']->where('payment_status', 'paid')->sum('final_cost');
            $data['totalServices'] = $data['items']->count();
        } else {
            $data['items'] = $this->getSellQuery()->get();
            $data['totalAgreed'] = $data['items']->where('status', 'paid')->sum('agreed_price');
            $data['totalSells'] = $data['items']->count();
        }

        $pdf = Pdf::loadView('pdf.report', $data);
        $pdf->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, "Laporan_{$this->reportType}_{$this->startDate}_sd_{$this->endDate}.pdf");
    }

    private function getOrderQuery()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        return Order::with(['orderItems', 'user'])
            ->whereBetween('created_at', [$start, $end])
            ->when($this->statusFilter !== 'all', function ($q) {
                if (in_array($this->statusFilter, ['paid', 'unpaid', 'refunded'])) {
                    $q->where('payment_status', $this->statusFilter);
                } else {
                    $q->where('status', $this->statusFilter);
                }
            })
            ->latest();
    }

    private function getServiceQuery()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        return ServiceOrder::with(['category', 'technician'])
            ->whereBetween('created_at', [$start, $end])
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->latest();
    }

    private function getSellQuery()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        return SellSubmission::with('category')
            ->whereBetween('created_at', [$start, $end])
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->latest();
    }

    public function render()
    {
        $summary = [];

        if ($this->reportType === 'penjualan') {
            $query = $this->getOrderQuery();
            $totalNominal = (clone $query)->where('payment_status', 'paid')->sum('total');
            $totalCount = (clone $query)->count();
            $paidCount = (clone $query)->where('payment_status', 'paid')->count();
            
            $summary = [
                'total_nominal' => $totalNominal,
                'total_count' => $totalCount,
                'paid_count' => $paidCount,
            ];
            $items = $query->paginate(15);
        } elseif ($this->reportType === 'servis') {
            $query = $this->getServiceQuery();
            $totalNominal = (clone $query)->where('payment_status', 'paid')->sum('final_cost');
            $totalCount = (clone $query)->count();
            $completedCount = (clone $query)->where('status', 'completed')->count();

            $summary = [
                'total_nominal' => $totalNominal,
                'total_count' => $totalCount,
                'completed_count' => $completedCount,
            ];
            $items = $query->paginate(15);
        } else {
            $query = $this->getSellQuery();
            $totalNominal = (clone $query)->where('status', 'paid')->sum('agreed_price');
            $totalCount = (clone $query)->count();
            $acceptedCount = (clone $query)->whereIn('status', ['accepted', 'paid', 'in_repair', 'ready_for_sale'])->count();

            $summary = [
                'total_nominal' => $totalNominal,
                'total_count' => $totalCount,
                'accepted_count' => $acceptedCount,
            ];
            $items = $query->paginate(15);
        }

        return view('livewire.admin.report-index', [
            'items' => $items,
            'summary' => $summary,
        ]);
    }
}
