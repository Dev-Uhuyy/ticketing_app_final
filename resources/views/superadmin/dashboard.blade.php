<x-layouts.admin title="Dashboard Admin">
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Light Mode Chart Globals
                Chart.defaults.color = '#64748b'; // Tailwind slate-500
                Chart.defaults.font.family = "'Inter', 'Segoe UI', Roboto, sans-serif";

                // Revenue Chart
                const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
                new Chart(ctxRevenue, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartDates ?? []) !!},
                        datasets: {!! json_encode($chartRevenueDatasets ?? []) !!}
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { backgroundColor: 'rgba(255, 255, 255, 0.9)', titleColor: '#1e293b', bodyColor: '#475569', borderColor: '#e2e8f0', borderWidth: 1 }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
                            y: { grid: { color: '#f1f5f9', borderDash: [5, 5] }, ticks: { color: '#94a3b8' } }
                        }
                    }
                });

                // Orders Chart
                const ctxOrders = document.getElementById('ordersChart').getContext('2d');
                new Chart(ctxOrders, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartDates ?? []) !!},
                        datasets: [{
                            label: 'Jumlah Transaksi',
                            data: {!! json_encode($chartOrders ?? []) !!},
                            backgroundColor: '#11408cff', // Blue 500
                            borderRadius: 6,
                            hoverBackgroundColor: '#60a5fa' // Blue 400
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { backgroundColor: 'rgba(255, 255, 255, 0.9)', titleColor: '#1e293b', bodyColor: '#475569', borderColor: '#e2e8f0', borderWidth: 1 }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
                            y: { grid: { color: '#f1f5f9', borderDash: [5, 5] }, ticks: { color: '#94a3b8', stepSize: 1 } }
                        }
                    }
                });
            });
        </script>
    @endpush

    <!-- Modern Light UI Wrapper -->
    <div
        class="min-h-screen bg-slate-50 text-slate-800 p-4 md:p-8 font-sans selection:bg-blue-200 selection:text-blue-900">

        <header class="mb-8 flex items-center gap-4">
            <div
                class="w-11 h-11 rounded-full md:rounded-2xl border border-slate-200 bg-white flex items-center justify-center shadow-sm">
                <div class="w-7 h-7 rounded-full bg-[#1e293b] flex items-center justify-center text-white">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <div>
                <h1 class="text-[22px] font-bold tracking-tight text-[#0f172a] leading-none mb-1">
                    Dashboard Superadmin
                </h1>
                <p class="text-[13px] text-slate-400 font-medium">Monitor performa sistem dan transaksi Anda</p>
            </div>
        </header>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <!-- Metric 1: Revenue -->
            <div
                class="bg-white rounded-[16px] p-[20px] border border-slate-100 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] flex justify-between items-center transition-shadow hover:shadow-[0_4px_15px_-4px_rgba(0,0,0,0.08)]">
                <div class="flex flex-col">
                    <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase mb-2">Total
                        Pendapatan</span>
                    <span class="text-[26px] font-black text-[#1e293b] leading-none mb-1.5 flex items-center">
                        <span
                            class="text-[16px] mr-1 font-bold">Rp</span>{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                    </span>
                    <a href="{{ route('superadmin.histories.index') }}"
                        class="text-[12px] text-slate-400 hover:text-slate-600 transition-colors">Semua pendapatan
                        Anda</a>
                </div>
                <!-- Icon: Calendar-like (dark blue/indigo) -->
                <div
                    class="w-[42px] h-[42px] rounded-[12px] bg-[#f8fafc] flex items-center justify-center bg-emerald-50 text-emerald-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Metric 2: Transactions (Check circle green) -->
            <div
                class="bg-white rounded-[16px] p-[20px] border border-slate-100 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] flex justify-between items-center transition-shadow hover:shadow-[0_4px_15px_-4px_rgba(0,0,0,0.08)]">
                <div class="flex flex-col">
                    <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase mb-2">Total
                        Transaksi</span>
                    <span class="text-[28px] font-black text-[#1e293b] leading-none mb-1.5">
                        {{ $totalOrders ?? 0 }}
                    </span>
                    <a href="{{ route('superadmin.histories.index') }}"
                        class="text-[12px] text-slate-400 hover:text-slate-600 transition-colors">Transaksi berhasil</a>
                </div>
                <!-- Icon: Check Circle (green) -->
                <div
                    class="w-[42px] h-[42px] rounded-[12px] bg-[#ecfdf5] flex items-center justify-center text-[#10b981]">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <!-- Metric 3: Events/Kategori (Ticket blue) -->
            <div
                class="bg-white rounded-[16px] p-[20px] border border-slate-100 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] flex justify-between items-center transition-shadow hover:shadow-[0_4px_15px_-4px_rgba(0,0,0,0.08)]">
                <div class="flex flex-col">
                    <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase mb-2">Total
                        Kategori</span>
                    <span class="text-[28px] font-black text-[#1e293b] leading-none mb-1.5">
                        {{ $totalEvents ?? 0 }}
                    </span>
                    <a href="{{ route('superadmin.categories.index') }}"
                        class="text-[12px] text-slate-400 hover:text-slate-600 transition-colors">Kategori aktif</a>
                </div>
                <!-- Icon: Ticket (blue) -->
                <div
                    class="w-[42px] h-[42px] rounded-[12px] bg-[#eff6ff] flex items-center justify-center text-[#3b82f6]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Metric 4: Users (Star warning/amber) -->
            <div
                class="bg-white rounded-[16px] p-[20px] border border-slate-100 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] flex justify-between items-center transition-shadow hover:shadow-[0_4px_15px_-4px_rgba(0,0,0,0.08)]">
                <div class="flex flex-col">
                    <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase mb-2">Total
                        Pengguna</span>
                    <span class="text-[28px] font-black text-[#1e293b] leading-none mb-1.5">
                        {{ $totalUsers ?? 0 }}
                    </span>
                    <a href="{{ route('superadmin.users.index') }}"
                        class="text-[12px] text-slate-400 hover:text-slate-600 transition-colors">Semua akun
                        pengguna</a>
                </div>
                <!-- Icon: Star (Amber) -->
                <div
                    class="w-[42px] h-[42px] rounded-[12px] bg-[#fffbeb] flex items-center justify-center text-[#f59e0b]">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Tren Pendapatan</h3>
                    <select onchange="window.location.href = '?filter=' + this.value"
                        class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                        <option value="7days" {{ ($filter ?? '7days') == '7days' ? 'selected' : '' }}>7 Hari Terakhir
                        </option>
                        <option value="month" {{ ($filter ?? '7days') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </div>
                <div class="relative h-80 w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Orders Chart -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Volume Transaksi</h3>
                    <select onchange="window.location.href = '?filter=' + this.value"
                        class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-800 block p-2">
                        <option value="7days" {{ ($filter ?? '7days') == '7days' ? 'selected' : '' }}>7 Hari Terakhir
                        </option>
                        <option value="month" {{ ($filter ?? '7days') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </div>
                <div class="relative h-80 w-full">
                    <!-- 🔵 GANTI WARNA DI SINI -->
                    <canvas id="ordersChart" data-color="#10B981"></canvas>
                </div>
            </div>
        </div>

        <!-- System Configuration & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Configuration Menus -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col gap-4">
                <h3 class="text-lg font-bold text-slate-800 mb-2">Manajemen Sistem</h3>

                <a href="{{ route('superadmin.vouchers.index') }}"
                    class="group p-5 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-white hover:border-indigo-200 hover:shadow-md transition-all flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div
                            class="w-14 h-14 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-800 font-bold text-lg">Voucher Diskon</p>
                            <p class="text-sm text-slate-500">{{ $totalVouchers ?? 0 }} Voucher Aktif</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-600 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ route('superadmin.payment-methods.index') }}"
                    class="group p-5 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-white hover:border-amber-200 hover:shadow-md transition-all flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div
                            class="w-14 h-14 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-800 font-bold text-lg">Metode Pembayaran</p>
                            <p class="text-sm text-slate-500">{{ $totalPaymentMethods ?? 0 }} Metode Tersedia</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-amber-500 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Recent Transactions Table -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100 overflow-hidden">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Transaksi Terbaru</h3>
                    <a href="{{ route('superadmin.histories.index') }}"
                        class="text-blue-600 bg-blue-50 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-blue-100 transition">Lihat
                        Semua Data</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="text-slate-500 border-b border-slate-200 text-xs uppercase tracking-wider bg-slate-50/50">
                                <th class="py-3 px-4 font-semibold rounded-tl-lg">ID Pesanan</th>
                                <th class="py-3 px-4 font-semibold">Pelanggan</th>
                                <th class="py-3 px-4 font-semibold">Acara/Event</th>
                                <th class="py-3 px-4 font-semibold text-right rounded-tr-lg">Nominal Bayar</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse ($recentOrders ?? [] as $order)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-4 text-slate-600 font-mono text-xs">
                                        #{{ \Illuminate\Support\Str::upper(substr(md5($order->id), 0, 8)) }}</td>
                                    <td class="py-4 px-4 flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                                            {{ substr(optional($order->user)->name ?? 'G', 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span
                                                class="text-slate-900 font-semibold">{{ optional($order->user)->name ?? 'Guest' }}</span>
                                            <span
                                                class="text-xs text-slate-500">{{ optional($order->order_date)->diffForHumans() ?? '' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span
                                            class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                                            {{ optional($order->event)->judul ?? 'Event Umum' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <span class="font-bold text-emerald-600">Rp
                                            {{ number_format($order->total_harga ?? 0, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-slate-500 bg-slate-50/50 rounded-b-lg">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                </path>
                                            </svg>
                                            <p>Belum ada data transaksi bulan ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>