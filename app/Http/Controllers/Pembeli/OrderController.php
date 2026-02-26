<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use App\Models\Event;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Review;
use App\Models\Tiket;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Menampilkan halaman checkout
    public function checkout(Request $request)
    {
        $eventId = $request->input('event_id');
        $items = $request->input('items', []);

        if (!$eventId || empty($items)) {
            return redirect()->route('home')->with('error', 'Data pesanan tidak valid');
        }

        $event = Event::with('tikets')->findOrFail($eventId);
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        // Ambil voucher yang aktif dan masih berlaku
        $vouchers = Voucher::where('aktif', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_kadaluarsa', '>=', now())
            ->where(function ($query) {
                $query->whereNull('penggunaan_maksimal')
                    ->orWhereColumn('jumlah_digunakan', '<', 'penggunaan_maksimal');
            })
            ->get();

        $cartItems = [];
        $subtotal = 0;

        if (is_array($items)) {
            foreach ($items as $item) {
                if (isset($item['tiket_id']) && isset($item['jumlah'])) {
                    $tiket = Tiket::find($item['tiket_id']);
                    if ($tiket) {
                        $qty = (int) $item['jumlah'];
                        $price = $tiket->harga ?? 0;
                        $itemTotal = $price * $qty;

                        $cartItems[] = [
                            'tiket' => $tiket,
                            'jumlah' => $qty,
                            'subtotal' => $itemTotal
                        ];

                        $subtotal += $itemTotal;
                    }
                }
            }
        }

        $user = Auth::user();

        return view('pembeli.checkout.index', [
            'event' => $event,
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'paymentMethods' => $paymentMethods,
            'vouchers' => $vouchers,
            'user' => $user
        ]);
    }

    // Validasi voucher via AJAX
    public function validateVoucher(Request $request)
    {
        $code = $request->input('code');
        $subtotal = $request->input('subtotal', 0);

        $voucher = Voucher::where('code', $code)
            ->where('aktif', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_kadaluarsa', '>=', now())
            ->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Voucher tidak valid atau sudah kadaluarsa']);
        }

        if ($voucher->jumlah_digunakan >= $voucher->penggunaan_maksimal) {
            return response()->json(['valid' => false, 'message' => 'Voucher sudah mencapai batas penggunaan']);
        }

        // Hitung diskon
        $diskon = 0;
        if ($voucher->tipe_diskon === 'percent') {
            $diskon = ($subtotal * $voucher->diskon) / 100;
        } else {
            $diskon = $voucher->diskon;
        }

        return response()->json([
            'valid' => true,
            'voucher_id' => $voucher->id,
            'diskon' => $diskon,
            'message' => 'Voucher berhasil diterapkan!'
        ]);
    }

    public function index()
    {
        $user = Auth::user() ?? \App\Models\User::first();
        $orders = Order::where('user_id', $user->id)->with('event')->orderBy('created_at', 'desc')->get();

        $reviewedEventIds = Review::where('user_id', Auth::id())
            ->pluck('event_id')
            ->toArray();

        return view('pembeli.orders.index', compact('orders', 'reviewedEventIds'));
    }

    // show a specific order
    public function show(Order $order)
    {
        $order->load('detailOrders.tiket', 'event', 'paymentMethod');
        return view('pembeli.orders.show', compact('order'));
    }

    // store an order
    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'items' => 'required|array|min:1',
            'items.*.tiket_id' => 'required|integer|exists:tikets,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'nama_pemesan' => 'required|string|max:255',
            'email_pemesan' => 'required|email|max:255',
            'no_telp' => 'required|string|max:20',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $user = Auth::user();

        try {
            // transaction
            $order = DB::transaction(function () use ($data, $user) {
                $total = 0;
                // validate stock and calculate total
                foreach ($data['items'] as $it) {
                    $t = Tiket::lockForUpdate()->findOrFail($it['tiket_id']);
                    if ($t->stok < $it['jumlah']) {
                        throw new \Exception("Stok tidak cukup untuk tipe: {$t->tipe}");
                    }
                    $total += ($t->harga ?? 0) * $it['jumlah'];
                }

                // Hitung diskon jika ada voucher
                $diskonAmount = 0;
                if (!empty($data['voucher_id'])) {
                    $voucher = Voucher::findOrFail($data['voucher_id']);

                    // Validasi Voucher
                    if (!$voucher->aktif) {
                        throw new \Exception("Voucher tidak aktif.");
                    }
                    if ($voucher->tanggal_kadaluarsa && \Carbon\Carbon::now()->greaterThan($voucher->tanggal_kadaluarsa)) {
                        throw new \Exception("Voucher sudah kadaluarsa.");
                    }
                    if ($voucher->penggunaan_maksimal > 0 && $voucher->jumlah_digunakan >= $voucher->penggunaan_maksimal) {
                        throw new \Exception("Voucher sudah habis digunakan.");
                    }

                    // Validasi untuk voucher tipe fixed: tidak bisa digunakan jika nominal voucher > total harga
                    if ($voucher->tipe_diskon === 'fixed' && $voucher->diskon > $total) {
                        throw new \Exception("Voucher tidak dapat digunakan karena nominal voucher lebih besar dari total harga tiket.");
                    }

                    if ($voucher->tipe_diskon === 'percent') {
                        $diskonAmount = ($total * $voucher->diskon) / 100;
                    } else {
                        $diskonAmount = $voucher->diskon;
                    }

                    // Update jumlah penggunaan voucher
                    $voucher->increment('jumlah_digunakan');
                }

                $totalBayar = $total - $diskonAmount;

                $order = Order::create([
                    'user_id' => $user->id,
                    'event_id' => $data['event_id'],
                    'order_date' => Carbon::now(),
                    'total_harga' => $total,
                    'nama_pemesan' => $data['nama_pemesan'],
                    'email_pemesan' => $data['email_pemesan'],
                    'no_telp' => $data['no_telp'],
                    'voucher_id' => $data['voucher_id'] ?? null,
                    'diskon_amount' => $diskonAmount,
                    'payment_method_id' => $data['payment_method_id'],
                    'total_bayar' => $totalBayar,
                    'status_pembayaran' => 'pending',
                ]);

                foreach ($data['items'] as $it) {
                    $t = Tiket::findOrFail($it['tiket_id']);
                    $subtotal = ($t->harga ?? 0) * $it['jumlah'];
                    DetailOrder::create([
                        'order_id' => $order->id,
                        'tiket_id' => $t->id,
                        'jumlah' => $it['jumlah'],
                        'subtotal_harga' => $subtotal,
                    ]);

                    // reduce stock
                    $t->stok = max(0, $t->stok - $it['jumlah']);
                    $t->save();
                }

                return $order;
            });

            // flash success message to session so it appears after redirect
            session()->flash('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');

            return redirect()->route('orders.index');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    // Reviews
    public function createReview(Event $event)
    {
        // Cek apakah user sudah pernah review event ini
        $alreadyReviewed = Review::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Anda sudah memberi review.');
        }

        return view('reviews.create', compact('event'));
    }

    public function storeReview(Request $request, Event $event)
    {
        $request->validate([
            'rate'   => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id'  => Auth::id(),
            'event_id' => $event->id,
            'rate'     => $request->rate,
            'review'   => $request->review,
            'answer'   => 'Belum dibalas',
        ]);

        return redirect()->route('events.show', $event)
            ->with('success', 'Review berhasil dikirim.');
    }
}
