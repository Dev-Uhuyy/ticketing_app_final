<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

use App\Models\Order;

use Illuminate\Http\Request;

class HistoriesController extends Controller
{
    public function index()
    {
        $histories = Order::latest()->get();
        return view('superadmin.history.index', compact('histories'));
    }

    public function show(string $history)
    {
        $order = Order::with('detailOrders.tiket', 'event', 'paymentMethod', 'user')->findOrFail($history);
        return view('superadmin.history.show', compact('order'));
    }


}
