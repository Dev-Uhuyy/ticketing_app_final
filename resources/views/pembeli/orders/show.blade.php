<x-layouts.app>
    <section class="max-w-4xl mx-auto py-12 px-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Detail Pemesanan</h1>
            <div class="text-sm text-gray-500">
                Order #{{ $order->id }} • {{ $order->order_date->translatedFormat('d F Y, H:i') }}
            </div>
        </div>

        <div class="card bg-base-100 shadow-md">
            <div class="lg:flex">
                <div class="lg:w-1/3 p-4">
                    <img src="{{ $order->event?->gambar ? asset($order->event->gambar) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
                        alt="{{ $order->event?->judul ?? 'Event' }}" class="w-full object-cover mb-2" />
                    <h2 class="font-semibold text-lg">{{ $order->event?->judul ?? 'Event' }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $order->event?->lokasi ?? '' }}</p>
                </div>

                <div class="card-body lg:w-2/3">
                    <div class="space-y-3">
                        @foreach ($order->detailOrders as $d)
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="font-bold">{{ $d->tiket->tipe }}</div>
                                    <div class="text-sm text-gray-500">Qty: {{ $d->jumlah }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold">Rp {{ number_format($d->subtotal_harga, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="divider"></div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                        </div>

                        @if ($order->diskon_amount > 0)
                            <div class="flex justify-between items-center text-sm text-success">
                                <span>Diskon (Voucher)</span>
                                <span>- Rp {{ number_format($order->diskon_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center mt-2 pt-2 border-t font-bold text-lg">
                            <span>Total Bayar</span>
                            <span>Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center text-sm mt-4 pt-4 border-t">
                            <span class="font-semibold">Metode Pembayaran</span>
                            <span class="flex items-center gap-2">
                                @if ($order->paymentMethod?->icon)
                                    <span>{{ $order->paymentMethod->icon }}</span>
                                @endif
                                {{ $order->paymentMethod?->name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('orders.index') }}" class="btn btn-primary text-white">Kembali ke Riwayat Pembelian</a>
        </div>
    </section>
</x-layouts.app>
