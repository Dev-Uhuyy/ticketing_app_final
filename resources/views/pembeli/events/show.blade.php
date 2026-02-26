<x-layouts.app>
    <section class="max-w-7xl mx-auto py-12 px-6">
        <nav class="mb-6">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="{{ route('home') }}" class="link link-neutral">Beranda</a></li>
                    <li><a href="#" class="link link-neutral">Event</a></li>
                    <li>{{ $event->judul }}</li>
                </ul>
            </div>
        </nav>

        <div class="max-w-4xl mx-auto">
            <!-- Left / Main area -->
            <div class="col-span-full">
                <div class="card bg-base-100 shadow">
                    <figure>
                        <img src="{{ $event->gambar ? asset('storage/' . $event->gambar) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
                            alt="{{ $event->judul }}" class="w-full h-96 object-cover" />
                    </figure>
                    <div class="card-body">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h1 class="text-3xl font-extrabold">{{ $event->judul }}</h1>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($event->tanggal_waktu)->locale('id')->translatedFormat('d F Y, H:i') }}
                                    • 📍
                                    {{ $event->lokasi }}
                                </p>

                                <div class="mt-3 flex gap-2 items-center">
                                    <span
                                        class="badge badge-primary">{{ $event->kategori?->nama ?? 'Tanpa Kategori' }}</span>
                                    <span class="badge">{{ $event->user?->name ?? 'Penyelenggara' }}</span>
                                </div>
                            </div>
                        </div>

                        <p class="mt-4 text-gray-700 leading-relaxed">{{ $event->deskripsi }}</p>

                        <div class="divider"></div>

                        <h3 class="text-xl font-bold">Pilih Tiket</h3>

                        <div class="mt-4 space-y-4">
                            @forelse($event->tikets as $tiket)
                                <div class="card card-side shadow-sm p-4 items-center">
                                    <div class="flex-1">
                                        <h4 class="font-bold">{{ $tiket->tipe }}</h4>
                                        <p class="text-sm text-gray-500">Stok: <span
                                                id="stock-{{ $tiket->id }}">{{ $tiket->stok }}</span></p>
                                        <p class="text-sm mt-2">{{ $tiket->keterangan ?? '' }}</p>
                                    </div>

                                    <div class="w-44 text-right">
                                        <div class="text-lg font-bold">
                                            {{ $tiket->harga ? 'Rp ' . number_format($tiket->harga, 0, ',', '.') : 'Gratis' }}
                                        </div>

                                        <div class="mt-3 flex items-center justify-end gap-2">
                                            <button type="button" class="btn btn-sm btn-outline" data-action="dec"
                                                data-id="{{ $tiket->id }}" aria-label="Kurangi satu">−</button>
                                            <input id="qty-{{ $tiket->id }}" type="number" min="0"
                                                max="{{ $tiket->stok }}" value="0"
                                                class="input input-bordered w-16 text-center"
                                                data-id="{{ $tiket->id }}" />
                                            <button type="button" class="btn btn-sm btn-outline" data-action="inc"
                                                data-id="{{ $tiket->id }}" aria-label="Tambah satu">+</button>
                                        </div>

                                        <div class="text-sm text-gray-500 mt-2">Subtotal: <span
                                                id="subtotal-{{ $tiket->id }}">Rp 0</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">Tiket belum tersedia untuk acara ini.</div>
                            @endforelse
                        <div class="mt-8 flex justify-end">
                            @auth
                                <button id="checkoutButton" class="btn btn-primary !bg-blue-900 text-white w-full md:w-auto px-8"
                                    onclick="openCheckout()" disabled>Checkout</button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary w-full md:w-auto px-8 text-white">Login untuk
                                    Checkout</a>
                            @endauth
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>

    <script>
        (function() {
            // Helper to format Indonesian currency
            const formatRupiah = (value) => {
                return 'Rp ' + Number(value).toLocaleString('id-ID');
            }

            const tickets = {
                @foreach ($event->tikets as $tiket)
                    {{ $tiket->id }}: {
                        id: {{ $tiket->id }},
                        price: {{ $tiket->harga ?? 0 }},
                        stock: {{ $tiket->stok }},
                        tipe: "{{ e($tiket->tipe) }}"
                    },
                @endforeach
            };

            const checkoutButton = document.getElementById('checkoutButton');

            function updateSummary() {
                let totalQty = 0;

                Object.values(tickets).forEach(t => {
                    const qtyInput = document.getElementById('qty-' + t.id);
                    if (!qtyInput) return;
                    const qty = Number(qtyInput.value || 0);
                    if (qty > 0) {
                        totalQty += qty;
                    }
                });

                if (checkoutButton) checkoutButton.disabled = totalQty === 0;
            }

            // Wire up plus/minus buttons and manual input
            document.querySelectorAll('[data-action="inc"]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.id;
                    const input = document.getElementById('qty-' + id)
                    const info = tickets[id];
                    if (!input || !info) return;
                    let val = Number(input.value || 0);
                    if (val < info.stock) val++;
                    input.value = val;
                    updateTicketSubtotal(id);
                    updateSummary();
                });
            });

            document.querySelectorAll('[data-action="dec"]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.id;
                    const input = document.getElementById('qty-' + id);
                    if (!input) return;
                    let val = Number(input.value || 0);
                    if (val > 0) val--;
                    input.value = val;
                    updateTicketSubtotal(id);
                    updateSummary();
                });
            });

            document.querySelectorAll('input[id^="qty-"]').forEach(input => {
                input.addEventListener('change', (e) => {
                    const el = e.currentTarget;
                    const id = el.dataset.id;
                    const info = tickets[id];
                    let val = Number(el.value || 0);
                    if (val < 0) val = 0;
                    if (val > info.stock) val = info.stock;
                    el.value = val;
                    updateTicketSubtotal(id);
                    updateSummary();
                });
            });

            function updateTicketSubtotal(id) {
                const t = tickets[id];
                const qty = Number(document.getElementById('qty-' + id).value || 0);
                const subtotalEl = document.getElementById('subtotal-' + id);
                if (subtotalEl) subtotalEl.textContent = formatRupiah(qty * t.price);
            }

            // Direct Checkout (skipping modal)
            window.openCheckout = function() {
                const btn = document.getElementById('checkoutButton');
                btn.setAttribute('disabled', 'disabled');
                btn.textContent = 'Memproses...';

                // gather items
                const items = [];
                Object.values(tickets).forEach(t => {
                    const qty = Number(document.getElementById('qty-' + t.id).value ||
                    0);
                    if (qty > 0) items.push({
                        tiket_id: t.id,
                        jumlah: qty
                    });
                });

                if (items.length === 0) {
                    alert('Tidak ada tiket dipilih');
                    btn.removeAttribute('disabled');
                    btn.textContent = 'Checkout';
                    return;
                }

                // Redirect to checkout page with items data
                const params = new URLSearchParams();
                params.append('event_id', {{ $event->id }});

                items.forEach((item, index) => {
                    params.append(`items[${index}][tiket_id]`, item.tiket_id);
                    params.append(`items[${index}][jumlah]`, item.jumlah);
                });

                window.location.href = "{{ route('checkout') }}?" + params.toString();
            }

            // init
            updateSummary();
        })();
    </script>
</x-layouts.app>
