<x-layouts.app>
    <section class="max-w-6xl mx-auto py-12 px-6">
        <!-- Success/Error Notifications -->
        @if(session('success'))
            <div class="alert alert-success mb-6 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error mb-6 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Riwayat Pembelian</h1>
        </div>

        <div class="space-y-4">
            @forelse($orders as $order)
                <article class="card lg:card-side bg-base-100 shadow-md overflow-hidden">
                    <figure class="lg:w-48">
                        <img src="{{ $order->event?->gambar ? asset($order->event->gambar) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
                            alt="{{ $order->event?->judul ?? 'Event' }}" class="w-full h-full object-cover" />
                    </figure>

                    <div class="card-body flex justify-between">
                        <div>
                            <div class="font-bold">Order #{{ $order->id }}</div>
                            <div class="text-sm text-gray-500 mt-1">
                                {{ $order->order_date->translatedFormat('d F Y, H:i') }}
                            </div>
                            <div class="text-sm mt-2">{{ $order->event?->judul ?? 'Event' }}</div>
                        </div>

                        <div class="text-right">
                            @if ($order->diskon_amount > 0)
                                <div class="text-xs text-gray-500 line-through">
                                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                </div>
                            @endif
                            <div class="font-bold text-lg">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</div>

                            @if (in_array($order->event_id, $reviewedEventIds))
                                <button class="btn btn-outline btn-primary btn-sm mt-3 opacity-50 cursor-not-allowed"
                                    disabled>
                                    Sudah Direview
                                </button>
                            @else
                                <a href="{{ route('reviews.create', $order->event) }}"
                                    class="btn btn-outline btn-primary btn-sm mt-3">
                                    Beri Rating
                                </a>
                            @endif

                            <a href="{{ route('orders.show', $order) }}"
                                class="btn btn-primary btn-sm mt-3 text-white">Lihat Detail</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="alert alert-info">Anda belum memiliki pesanan.</div>
            @endforelse
        </div>
    </section>

</x-layouts.app>
