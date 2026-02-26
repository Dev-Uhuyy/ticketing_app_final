<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

use App\Models\Event;
use App\Models\Order;
use App\Models\Tiket;
use App\Models\User;
use App\Models\Voucher;
use App\Models\PaymentMethod;
use App\Models\Kategori;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', '7days');

        $totalEvents = Event::count();
        $totalCategories = Kategori::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_harga');

        $totalPembeli = User::where('role', 'pembeli')->count();
        $totalPengelola = User::where('role', 'pengelola_event')->count();
        $totalUsers = User::count(); // or just overall users

        $totalVouchers = Voucher::count();
        $totalPaymentMethods = PaymentMethod::count();

        // Recent 5 orders
        $recentOrders = Order::with(['user', 'event'])->latest('order_date')->take(5)->get();

        // Chart Data Filtering
        $startDate = Carbon::now();
        if ($filter === 'month') {
            $startDate = Carbon::now()->startOfMonth();
        } else {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
        }

        $datesCollection = collect();
        $currentDate = clone $startDate;
        $now = Carbon::now()->endOfDay();

        while ($currentDate <= $now) {
            $datesCollection->push($currentDate->format('Y-m-d'));
            $currentDate->addDay();
        }

        $chartDates = [];
        $chartOrders = [];

        // 1. Transaction Volume (Orders overall)
        $orderDataRaw = Order::select(DB::raw('DATE(order_date) as date'), DB::raw('COUNT(*) as total'))
            ->where('order_date', '>=', $startDate)
            ->groupBy(DB::raw('DATE(order_date)'))
            ->get()
            ->keyBy('date');

        foreach ($datesCollection as $date) {
            $chartDates[] = Carbon::parse($date)->format('d M');
            $chartOrders[] = isset($orderDataRaw[$date]) ? $orderDataRaw[$date]->total : 0;
        }

        // 2. Revenue Trend by Category
        $kategoris = Kategori::all();
        $revenueDataRaw = Order::join('events', 'orders.event_id', '=', 'events.id')
            ->select(DB::raw('DATE(orders.order_date) as date'), 'events.kategori_id', DB::raw('SUM(orders.total_harga) as total'))
            ->where('orders.order_date', '>=', $startDate)
            ->groupBy(DB::raw('DATE(orders.order_date)'), 'events.kategori_id')
            ->get();

        $chartRevenueDatasets = [];
        $colors = [
            ['borderColor' => '#10b981', 'bgColor' => 'rgba(16, 185, 129, 0.15)'], // Emerald
            ['borderColor' => '#3b82f6', 'bgColor' => 'rgba(59, 130, 246, 0.15)'], // Blue
            ['borderColor' => '#8b5cf6', 'bgColor' => 'rgba(139, 92, 246, 0.15)'], // Purple
            ['borderColor' => '#f97316', 'bgColor' => 'rgba(249, 115, 22, 0.15)'], // Orange
            ['borderColor' => '#f43f5e', 'bgColor' => 'rgba(244, 63, 94, 0.15)'],  // Rose
            ['borderColor' => '#06b6d4', 'bgColor' => 'rgba(6, 182, 212, 0.15)'],  // Cyan
        ];

        foreach ($kategoris as $index => $kategori) {
            $data = [];
            foreach ($datesCollection as $date) {
                $val = $revenueDataRaw->where('date', $date)->where('kategori_id', $kategori->id)->first();
                $data[] = $val ? $val->total : 0;
            }

            if (array_sum($data) > 0 || $filter === '7days') {
                $color = $colors[$index % count($colors)];
                $chartRevenueDatasets[] = [
                    'label' => $kategori->nama ?? 'Unknown',
                    'data' => $data,
                    'borderColor' => $color['borderColor'],
                    'backgroundColor' => $color['bgColor'],
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => $color['borderColor'],
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'pointHoverBackgroundColor' => '#ffffff',
                    'pointHoverBorderColor' => $color['borderColor']
                ];
            }
        }

        // If no datasets exist matching criteria
        if (empty($chartRevenueDatasets)) {
            $chartRevenueDatasets[] = [
                'label' => 'Belum ada pendapatan',
                'data' => array_fill(0, count($chartDates), 0),
                'borderColor' => '#94a3b8',
                'backgroundColor' => 'rgba(148, 163, 184, 0.1)',
                'borderWidth' => 3,
                'fill' => true,
                'tension' => 0.4,
            ];
        }

        // 3. Category Summary Cards
        $categoryCards = Kategori::withCount('events')->get()->map(function ($cat) {
            $cat->revenue = Order::whereHas('event', function ($q) use ($cat) {
                $q->where('kategori_id', $cat->id);
            })->sum('total_harga');
            return $cat;
        });

        return view('superadmin.dashboard', compact(
            'totalEvents',
            'totalCategories',
            'totalOrders',
            'totalRevenue',
            'totalPembeli',
            'totalPengelola',
            'totalUsers',
            'totalVouchers',
            'totalPaymentMethods',
            'recentOrders',
            'chartDates',
            'chartRevenueDatasets',
            'chartOrders',
            'filter',
            'categoryCards'
        ));
    }
}