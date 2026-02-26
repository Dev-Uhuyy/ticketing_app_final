<x-layouts.app>
    <section class="max-w-7xl mx-auto py-12 px-6">
        <nav class="mb-6">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="{{ route('home') }}" class="link link-neutral">Beranda</a></li>
                    <li><a href="{{ route('events.show', $event->id) }}"
                            class="link link-neutral">{{ $event->judul }}</a></li>
                    <li>Checkout</li>
                </ul>
            </div>
        </nav>

        <h1 class="text-3xl font-extrabold mb-8">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Checkout -->
            <div class="lg:col-span-2">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body">
                        <h2 class="card-title text-xl font-bold mb-4">Informasi Pemesan</h2>

                        <form id="checkoutForm" action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <!-- Hidden inputs untuk items -->
                            @foreach ($cartItems as $index => $item)
                                <input type="hidden" name="items[{{ $index }}][tiket_id]"
                                    value="{{ $item['tiket']->id }}">
                                <input type="hidden" name="items[{{ $index }}][jumlah]"
                                    value="{{ $item['jumlah'] }}">
                            @endforeach

                            <!-- Nama Pemesan -->
                            <div class="form-control w-full mb-4">
                                <label class="label mb-2">
                                    <span class="label-text">Nama Lengkap <span class="text-error">*</span></span>
                                </label>
                                <input type="text" name="nama_pemesan" placeholder="Masukkan nama lengkap"
                                    class="input input-bordered w-full" required
                                    value="{{ $user ? $user->name : '' }}">
                            </div>

                            <!-- Email -->
                            <div class="form-control w-full mb-4">
                                <label class="label mb-2">
                                    <span class="label-text">Email <span class="text-error">*</span></span>
                                </label>
                                <input type="email" name="email_pemesan" placeholder="nama@email.com"
                                    class="input input-bordered w-full" required
                                    value="{{ $user ? $user->email : '' }}">
                            </div>

                            <!-- No. Telepon -->
                            <div class="form-control w-full mb-4">
                                <label class="label mb-2">
                                    <span class="label-text">No. Telepon <span class="text-error">*</span></span>
                                </label>
                                <input type="tel" name="no_telp" placeholder="08xxxxxxxxxx"
                                    class="input input-bordered w-full" required>
                            </div>

                            <!-- Voucher -->
                            <div class="form-control w-full mb-4">
                                <label class="label mb-2">
                                    <span class="label-text">Kode Voucher (Opsional)</span>
                                </label>
                                <select class="select select-bordered w-full" id="voucherSelect" name="voucher_id">
                                    <option value="">Pilih Voucher</option>
                                    @foreach ($vouchers as $voucher)
                                        @php
                                            $isDisabled = $voucher->tipe_diskon === 'fixed' && $voucher->diskon > $subtotal;
                                        @endphp
                                        <option value="{{ $voucher->id }}"
                                            data-diskon="{{ $voucher->diskon }}"
                                            data-tipe="{{ $voucher->tipe_diskon }}"
                                            @if($isDisabled) disabled @endif
                                            style="{{ $isDisabled ? 'opacity: 0.5; color: #999;' : '' }}">
                                            {{ $voucher->code }} -
                                            @if ($voucher->tipe_diskon === 'percent')
                                                {{ $voucher->diskon }}% OFF
                                            @else
                                                Rp {{ number_format($voucher->diskon, 0, ',', '.') }} OFF
                                            @endif
                                            @if($isDisabled)
                                                (Tidak dapat digunakan - nominal lebih besar dari total)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <label class="label">
                                    <span class="label-text-alt text-success" id="voucherMessage"></span>
                                </label>
                            </div>

                            <!-- Payment Method -->
                            <div class="form-control w-full mb-6">
                                <label class="label mb-2">
                                    <span class="label-text">Metode Pembayaran <span class="text-error">*</span></span>
                                </label>
                                <div class="space-y-3">
                                    @forelse($paymentMethods as $method)
                                        <label
                                            class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-base-200 transition-colors">
                                            <input type="radio" name="payment_method_id" value="{{ $method->id }}"
                                                class="radio radio-primary" required>
                                            <div class="flex items-center gap-3 flex-1">
                                                @if ($method->icon)
                                                    <span class="text-2xl">{{ $method->icon }}</span>
                                                @endif
                                                <div>
                                                    <div class="font-semibold">{{ $method->name }}</div>
                                                    @if ($method->description)
                                                        <div class="text-sm text-gray-500">{{ $method->description }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                    @empty
                                        <div class="alert alert-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="stroke-current shrink-0 h-6 w-6" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span>Belum ada metode pembayaran tersedia.</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div id="errorAlert" class="alert alert-error mb-4 hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span id="errorMessage"></span>
                            </div>

                            <button type="submit" id="submitBtn"
                                class="btn btn-primary !bg-blue-900 text-white w-full">
                                <span class="loading loading-spinner hidden" id="loadingSpinner"></span>
                                Proses Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="lg:col-span-1">
                <div class="card sticky top-24 p-4 bg-base-100 shadow-md">
                    <h4 class="font-bold text-lg">Ringkasan Pesanan</h4>

                    <div class="mt-4">
                        <h3 class="font-semibold text-sm text-gray-500 mb-2">{{ $event->judul }}</h3>
                        <div class="space-y-2">
                            @foreach ($cartItems as $item)
                                <div class="flex justify-between text-sm">
                                    <span>{{ $item['tiket']->tipe }} x{{ $item['jumlah'] }}</span>
                                    <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="divider my-2"></div>

                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Subtotal</span>
                            <span id="subtotalAmount">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between text-sm text-success" id="discountRow" style="display: none;">
                            <span>Diskon</span>
                            <span id="discountAmount">- Rp 0</span>
                        </div>

                        <div class="divider my-1"></div>

                        <div class="flex justify-between text-xl font-bold mt-1">
                            <span>Total</span>
                            <span id="totalAmount">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let subtotal = {{ $subtotal }};
                let discount = 0;

                const voucherSelect = document.getElementById('voucherSelect');
                const discountRow = document.getElementById('discountRow');
                const discountAmountEl = document.getElementById('discountAmount');
                const totalAmountEl = document.getElementById('totalAmount');
                const voucherMessageEl = document.getElementById('voucherMessage');

                function formatNumber(num) {
                    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                function updateTotal() {
                    const total = Math.max(0, subtotal - discount);
                    totalAmountEl.textContent = 'Rp ' + formatNumber(total);
                }

                if (voucherSelect) {
                    voucherSelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];

                        if (this.value) {
                            const diskonVal = parseFloat(selectedOption.dataset.diskon);
                            const tipe = selectedOption.dataset.tipe;

                            // Validasi untuk voucher tipe fixed
                            if (tipe === 'fixed' && diskonVal > subtotal) {
                                if (voucherMessageEl) {
                                    voucherMessageEl.textContent = 'Voucher tidak dapat digunakan karena nominal voucher lebih besar dari total harga tiket.';
                                    voucherMessageEl.classList.remove('text-success');
                                    voucherMessageEl.classList.add('text-error');
                                }
                                // Reset voucher selection
                                this.value = '';
                                resetVoucher();
                                updateTotal();
                                return;
                            }

                            if (tipe === 'percent') {
                                discount = (subtotal * diskonVal) / 100;
                            } else {
                                discount = diskonVal;
                            }

                            if (voucherMessageEl) {
                                voucherMessageEl.textContent = 'Voucher berhasil diterapkan!';
                                voucherMessageEl.classList.remove('text-error');
                                voucherMessageEl.classList.add('text-success');
                            }

                            if (discountRow) discountRow.style.display = 'flex';
                            if (discountAmountEl) discountAmountEl.textContent = '- Rp ' + formatNumber(discount);
                        } else {
                            resetVoucher();
                        }
                        updateTotal();
                    });
                }

                function resetVoucher() {
                    discount = 0;
                    if (voucherMessageEl) {
                        voucherMessageEl.textContent = '';
                        voucherMessageEl.classList.remove('text-success', 'text-error');
                    }
                    if (discountRow) discountRow.style.display = 'none';
                    if (discountAmountEl) discountAmountEl.textContent = '- Rp 0';
                }

                // Initial total update
                updateTotal();

                // Form submission
                const checkoutForm = document.getElementById('checkoutForm');
                if (checkoutForm) {
                    checkoutForm.addEventListener('submit', function(e) {
                        const submitBtn = document.getElementById('submitBtn');
                        const loadingSpinner = document.getElementById('loadingSpinner');

                        // Show loading state, but let the form submit naturally
                        if (submitBtn) submitBtn.disabled = true;
                        if (loadingSpinner) loadingSpinner.classList.remove('hidden');
                    });
                }
            });
        </script>
    @endpush
</x-layouts.app>
