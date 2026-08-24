<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\SellSubmission;
use App\Models\ServiceOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public int $chartPeriod = 7; // 7, 14, or 30 days
    public array $revenueChartData = [];
    public array $categoryChartData = [];
    public array $serviceStatusData = [];

    public function mount(): void
    {
        $this->loadChartData();
    }

    public function setPeriod(int $days): void
    {
        $this->chartPeriod = in_array($days, [7, 14, 30]) ? $days : 7;
        $this->loadChartData();
        $this->dispatch('chart-data-updated', [
            'revenue' => $this->revenueChartData,
            'category' => $this->categoryChartData,
            'service' => $this->serviceStatusData,
        ]);
    }

    private function loadChartData(): void
    {
        // 1. Revenue & Order Trends over selected period
        $labels = [];
        $revenueData = [];
        $orderCountData = [];

        $startDate = Carbon::today()->subDays($this->chartPeriod - 1)->startOfDay();

        // Single aggregate query for daily revenues
        $dailyRevenues = Order::where('payment_status', 'paid')
            ->where(function ($q) use ($startDate) {
                $q->where('paid_at', '>=', $startDate)
                  ->orWhere(function ($sub) use ($startDate) {
                      $sub->whereNull('paid_at')->where('created_at', '>=', $startDate);
                  });
            })
            ->selectRaw('DATE(COALESCE(paid_at, created_at)) as date_key, SUM(total) as total_revenue')
            ->groupBy('date_key')
            ->pluck('total_revenue', 'date_key')
            ->toArray();

        // Single aggregate query for daily orders
        $dailyOrders = Order::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date_key, COUNT(*) as total_orders')
            ->groupBy('date_key')
            ->pluck('total_orders', 'date_key')
            ->toArray();

        for ($i = $this->chartPeriod - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $key = $date->toDateString();
            $labels[] = $this->chartPeriod > 14 ? $date->format('d/m') : $date->translatedFormat('d M');

            $revenueData[] = (float) ($dailyRevenues[$key] ?? 0);
            $orderCountData[] = (int) ($dailyOrders[$key] ?? 0);
        }

        $this->revenueChartData = [
            'labels' => $labels,
            'revenues' => $revenueData,
            'orders' => $orderCountData,
        ];

        // 2. Sales by Category (Top Categories)
        $categories = Category::withCount(['products'])->get();
        $catLabels = [];
        $catCounts = [];
        $catColors = ['#0F172A', '#F59E0B', '#10B981', '#6366F1', '#EC4899', '#8B5CF6', '#14B8A6'];

        foreach ($categories as $cat) {
            $catLabels[] = $cat->name;
            $catCounts[] = (int) $cat->products_count;
        }

        if (empty($catLabels)) {
            $catLabels = ['Elektronik'];
            $catCounts = [1];
        }

        $this->categoryChartData = [
            'labels' => $catLabels,
            'data' => $catCounts,
            'colors' => array_slice($catColors, 0, count($catLabels)),
        ];

        // 3. Service Status Breakdown
        $servicePending = ServiceOrder::where('status', 'pending')->count();
        $serviceDiagnosing = ServiceOrder::whereIn('status', ['confirmed', 'diagnosing', 'waiting_approval'])->count();
        $serviceInProgress = ServiceOrder::where('status', 'in_progress')->count();
        $serviceCompleted = ServiceOrder::where('status', 'completed')->count();

        $this->serviceStatusData = [
            'labels' => ['Menunggu', 'Diagnosa', 'Dikerjakan', 'Selesai'],
            'data' => [$servicePending, $serviceDiagnosing, $serviceInProgress, $serviceCompleted],
            'colors' => ['#F59E0B', '#3B82F6', '#6366F1', '#10B981'],
        ];
    }

    public function render()
    {
        // Metric Stats
        $todayOrdersCount = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total');

        $thisMonthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $lastMonthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');

        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) 
            : 100;

        $pendingServices = ServiceOrder::where('status', 'pending')->count();
        $inProgressServices = ServiceOrder::where('status', 'in_progress')->count();
        $pendingSellSubmissions = SellSubmission::where('status', 'pending')->count();
        
        $readyProductsCount = Product::where('status', 'available')
            ->where('stock', '>', 0)
            ->count();

        $readyProductsValue = (float) Product::where('status', 'available')
            ->where('stock', '>', 0)
            ->sum('price');

        $totalCustomers = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['super_admin', 'admin', 'teknisi']);
        })->count();

        // 5 Latest Orders with relations
        $latestOrders = Order::with('orderItems')
            ->latest()
            ->take(5)
            ->get();

        // 5 Active / Priority Services
        $priorityServices = ServiceOrder::with('category')
            ->whereIn('status', ['pending', 'diagnosing', 'waiting_approval', 'in_progress'])
            ->latest()
            ->take(5)
            ->get();

        // 5 Recently listed products ready for sale
        $recentlyListedProducts = Product::with('category')
            ->where('status', 'available')
            ->latest()
            ->take(5)
            ->get();

        // 4 Latest Sell Submissions (Tukar Tambah / Jual Bekas)
        $latestSellSubmissions = SellSubmission::with('category')
            ->latest()
            ->take(4)
            ->get();

        return view('livewire.admin.dashboard', [
            'todayOrdersCount' => $todayOrdersCount,
            'todayRevenue' => $todayRevenue,
            'thisMonthRevenue' => $thisMonthRevenue,
            'revenueGrowth' => $revenueGrowth,
            'pendingServices' => $pendingServices,
            'inProgressServices' => $inProgressServices,
            'pendingSellSubmissions' => $pendingSellSubmissions,
            'readyProductsCount' => $readyProductsCount,
            'readyProductsValue' => $readyProductsValue,
            'totalCustomers' => $totalCustomers,
            'latestOrders' => $latestOrders,
            'priorityServices' => $priorityServices,
            'recentlyListedProducts' => $recentlyListedProducts,
            'latestSellSubmissions' => $latestSellSubmissions,
        ])->layout('layouts.admin');
    }
}