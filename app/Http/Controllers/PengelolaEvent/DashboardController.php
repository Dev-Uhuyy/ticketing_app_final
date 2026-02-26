<?php

namespace App\Http\Controllers\PengelolaEvent;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Review;
use App\Models\Tiket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $totalEvents = Event::where('user_id', $userId)->count();

        $activeEvents = Event::where('user_id', $userId)
            ->where('status', 'published')
            ->count();

        $ticketsSold = DB::table('detail_orders')
            ->join('tikets', 'detail_orders.tiket_id', '=', 'tikets.id')
            ->join('events', 'tikets.event_id', '=', 'events.id')
            ->where('events.user_id', $userId)
            ->sum('detail_orders.jumlah') ?? 0;

        $avgRating = Review::whereHas('event', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->avg('rate') ?? 0;

        $totalAllTickets = Tiket::whereHas('event', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->sum('stok');

        $topEvent = Event::where('user_id', $userId)
            ->withCount(['tikets as sold_count' => function ($query) {
                $query->join('detail_orders', 'tikets.id', '=', 'detail_orders.tiket_id')
                      ->select(DB::raw('COALESCE(SUM(detail_orders.jumlah), 0)'));
            }])
            ->orderBy('sold_count', 'desc')
            ->first();

        $bestDay = DB::table('detail_orders')
            ->join('tikets', 'detail_orders.tiket_id', '=', 'tikets.id')
            ->join('events', 'tikets.event_id', '=', 'events.id')
            ->select(
                DB::raw('DATE(detail_orders.created_at) as date'),
                DB::raw('SUM(detail_orders.jumlah) as total')
            )
            ->where('events.user_id', $userId)
            ->groupBy('date')
            ->orderBy('total', 'desc')
            ->first();

        $rawSales = DB::table('detail_orders')
            ->join('tikets', 'detail_orders.tiket_id', '=', 'tikets.id')
            ->join('events', 'tikets.event_id', '=', 'events.id')
            ->select(
                DB::raw('DATE(detail_orders.created_at) as date'),
                DB::raw('SUM(detail_orders.jumlah) as total')
            )
            ->where('events.user_id', $userId)
            ->where('detail_orders.created_at', '>=', Carbon::now()->startOfDay()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        $salesData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $salesData->push([
                'date'  => $date,
                'total' => isset($rawSales[$date]) ? (int) $rawSales[$date]->total : 0,
            ]);
        }

        $upcomingEvents = Event::where('user_id', $userId)
            ->where('tanggal_waktu_mulai', '>', Carbon::now())
            ->orderBy('tanggal_waktu_mulai', 'asc')
            ->take(5)
            ->get();

        $latestReviews = Review::with(['user', 'event'])
            ->whereHas('event', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('pengelola_event.dashboard', compact(
            'totalEvents',
            'activeEvents',
            'ticketsSold',
            'avgRating',
            'totalAllTickets',
            'topEvent',
            'bestDay',
            'salesData',
            'upcomingEvents',
            'latestReviews'
        ));
    }
}
