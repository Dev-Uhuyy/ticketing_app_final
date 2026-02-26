<x-layouts.app>
    <section class="max-w-6xl mx-auto py-12 px-6">
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
                            <div class="text-sm text-gray-500 mt-1">{{ $order->order_date->translatedFormat('d F Y, H:i') }}</div>
                            <div class="text-sm mt-2">{{ $order->event?->judul ?? 'Event' }}</div>
                        </div>

                        <div class="text-right">
                            @if ($order->diskon_amount > 0)
                                <div class="text-xs text-gray-500 line-through">
                                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                </div>
                            @endif
                            <div class="font-bold text-lg">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</div>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-primary mt-3 text-white">Lihat Detail</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="alert alert-info">Anda belum memiliki pesanan.</div>
            @endforelse
        </div>
    </section>

            <div class="text-right">
              <div class="font-bold text-lg">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
              @if(in_array($order->event_id, $reviewedEventIds))
                <button class="btn btn-outline btn-primary opacity-50 cursor-not-allowed" disabled>
                    Sudah Direview
                </button>
            @else
                <a href="{{ route('reviews.create', $order->event) }}" class="btn btn-outline btn-primary">
                    Beri Rating
                </a>
            @endif
              <a href="{{ route('orders.show', $order) }}" class="btn btn-primary  text-white">Lihat Detail</a>
            </div>
          </div>
        </article>
      @empty
        <div class="alert alert-info">Anda belum memiliki pesanan.</div>
      @endforelse
    </div>
  </section>

</x-layouts.app>
