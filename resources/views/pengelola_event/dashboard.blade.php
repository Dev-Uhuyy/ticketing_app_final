<x-layouts.admin title="Dashboard Admin">
    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --brand:       #1e3a8a;
            --brand-dark:  #172d6e;
            --brand-mid:   #3b5fc0;
            --brand-light: #eff4ff;
            --brand-border: #c7d7f9;
        }

        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .brand-btn-active {
            background-color: var(--brand) !important;
            color: #fff !important;
            box-shadow: 0 2px 8px rgba(30,58,138,.3);
        }
        .brand-text { color: var(--brand) !important; }

        /* Slim scrollbar */
        .slim-scroll::-webkit-scrollbar       { width: 4px; }
        .slim-scroll::-webkit-scrollbar-track { background: transparent; }
        .slim-scroll::-webkit-scrollbar-thumb { background: #c7d7f9; border-radius: 9999px; }

        /* Stat cards */
        .stat-card {
            background: #fff;
            border-radius: 0.65rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
            padding: 1rem 1.1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: box-shadow .2s, transform .2s;
        }
        .stat-card:hover {
            box-shadow: 0 4px 14px rgba(30,58,138,.1);
            transform: translateY(-1px);
        }
        .stat-icon {
            width: 42px; height: 42px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* Panel cards */
        .panel {
            background: #fff;
            border-radius: 0.65rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
            padding: 1.1rem;
        }
        .panel-title {
            font-size: .875rem;
            font-weight: 700;
            color: #1e1e2e;
            display: flex;
            align-items: center;
            gap: .45rem;
        }
        .panel-title-icon {
            width: 26px; height: 26px;
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: .68rem;
            background: var(--brand-light);
            color: var(--brand);
            flex-shrink: 0;
        }

        /* Table */
        .event-table {
            table-layout: fixed;
            width: 100%;
        }
        .event-table th {
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #9ca3af;
            font-weight: 600;
            padding-bottom: .5rem;
            padding-right: .5rem;
            white-space: nowrap;
            overflow: hidden;
        }
        .event-table td {
            padding: .5rem .5rem .5rem 0;
            vertical-align: middle;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .event-table col.col-name   { width: 35%; }
        .event-table col.col-date   { width: 25%; }
        .event-table col.col-loc    { width: 22%; }
        .event-table col.col-status { width: 18%; }
        .event-table tbody tr {
            border-top: 1px solid #f3f4f6;
            transition: background .15s;
        }
        .event-table tbody tr:hover { background: #f8faff; }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            padding: .18rem .55rem;
            border-radius: 5px;
            font-size: .63rem;
            font-weight: 600;
            line-height: 1;
        }
        .badge-dot {
            width: 5px; height: 5px;
            border-radius: 9999px;
        }

        /* Review item */
        .review-item {
            display: flex;
            gap: .6rem;
            padding: .6rem .5rem;
            border-radius: .45rem;
            transition: background .15s;
        }
        .review-item:hover { background: var(--brand-light); }
        .review-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: var(--brand-light);
            color: var(--brand);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            font-size: .72rem;
            flex-shrink: 0;
            border: 1px solid var(--brand-border);
        }

        /* Performance item */
        .perf-item {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .6rem .75rem;
            background: var(--brand-light);
            border-radius: .45rem;
            border: 1px solid var(--brand-border);
        }
        .perf-icon {
            width: 32px; height: 32px;
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: .76rem;
            flex-shrink: 0;
        }

        /* Page header */
        .page-header-icon {
            width: 36px; height: 36px;
            border-radius: 9px;
            background: var(--brand-light);
            color: var(--brand);
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem;
            border: 1px solid var(--brand-border);
        }
    </style>

    <div class="p-4 lg:p-6 space-y-4">

        {{-- Page Header --}}
        <div class="flex items-center gap-3">
            <div class="page-header-icon">
                <i class="fa-solid fa-gauge-high"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-900 leading-tight">Dashboard Pengelola Event</h1>
                <p class="text-gray-400 text-xs mt-0.5">Monitor performa event dan penjualan tiket Anda</p>
            </div>
        </div>

        {{-- Stat Cards (4 card, rating tetap) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            @php
                $stats = [
                    [
                        'label'  => 'Total Event',
                        'value'  => $totalEvents,
                        'icon'   => 'calendar-days',
                        'iconBg' => 'background:#eff4ff; color:#1e3a8a;',
                        'sub'    => 'Semua event Anda',
                    ],
                    [
                        'label'  => 'Event Aktif',
                        'value'  => $activeEvents,
                        'icon'   => 'circle-check',
                        'iconBg' => 'background:#ecfdf5; color:#059669;',
                        'sub'    => 'Sedang berlangsung',
                    ],
                    [
                        'label'  => 'Tiket Terjual',
                        'value'  => number_format($ticketsSold),
                        'icon'   => 'ticket',
                        'iconBg' => 'background:#e0eaff; color:#2552c8;',
                        'sub'    => 'Total akumulasi',
                    ],
                    [
                        'label'  => 'Rating Rata-rata',
                        'value'  => number_format($avgRating, 1),
                        'icon'   => 'star',
                        'iconBg' => 'background:#fefce8; color:#ca8a04;',
                        'sub'    => 'Dari semua review',
                    ],
                ];
            @endphp
            @foreach($stats as $stat)
            <div class="stat-card">
                <div>
                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">{{ $stat['label'] }}</p>
                    <h2 class="text-2xl font-extrabold text-gray-900 mt-0.5 leading-none">{{ $stat['value'] }}</h2>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $stat['sub'] }}</p>
                </div>
                <div class="stat-icon" style="{{ $stat['iconBg'] }}">
                    <i class="fa-solid fa-{{ $stat['icon'] }}"></i>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Chart + Performance Summary --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Chart --}}
            <div class="lg:col-span-2 panel">
                <div class="flex flex-wrap justify-between items-center gap-2 mb-3">
                    <h3 class="panel-title">
                        <span class="panel-title-icon"><i class="fa-solid fa-chart-line"></i></span>
                        Total Tiket Terjual
                    </h3>
                    <div class="flex items-center bg-gray-100 rounded-lg p-0.5">
                        <button class="px-3 py-1 text-[11px] font-semibold rounded-md brand-btn-active transition-all">
                            <i class="fa-solid fa-clock-rotate-left mr-1 opacity-75"></i>7 Hari Terakhir
                        </button>
                    </div>
                </div>
                <div class="h-52">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            {{-- Performance Summary — tanpa Rating --}}
            <div class="panel">
                <h3 class="panel-title mb-3">
                    <span class="panel-title-icon"><i class="fa-solid fa-bolt"></i></span>
                    Ringkasan Performa
                </h3>
                <div class="space-y-2">
                    <div class="perf-item">
                        <div class="perf-icon" style="background:#dbeafe; color:#1e3a8a;">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] text-gray-400">Total semua tiket (stok)</p>
                            <p class="font-bold text-xs text-gray-800 mt-0.5">{{ number_format($totalAllTickets) }} tiket</p>
                        </div>
                    </div>
                    <div class="perf-item">
                        <div class="perf-icon" style="background:#e0eaff; color:#2552c8;">
                            <i class="fa-solid fa-trophy"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] text-gray-400">Event paling laris</p>
                            <p class="font-bold text-xs text-gray-800 mt-0.5 truncate">
                                {{ $topEvent ? $topEvent->judul : '-' }}
                            </p>
                            @if($topEvent)
                                <p class="text-[10px] text-gray-400">{{ number_format($topEvent->sold_count) }} tiket terjual</p>
                            @endif
                        </div>
                    </div>
                    <div class="perf-item">
                        <div class="perf-icon" style="background:#c7d7f9; color:#172d6e;">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] text-gray-400">Hari penjualan tertinggi</p>
                            <p class="font-bold text-xs text-gray-800 mt-0.5">
                                {{ $bestDay ? \Carbon\Carbon::parse($bestDay->date)->translatedFormat('l, d M Y') : '-' }}
                            </p>
                            @if($bestDay)
                                <p class="text-[10px] text-gray-400">{{ number_format($bestDay->total) }} tiket terjual</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Upcoming Events + Latest Reviews --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- Upcoming Events --}}
            <div class="panel">
                <h3 class="panel-title mb-3">
                    <span class="panel-title-icon"><i class="fa-solid fa-calendar-check"></i></span>
                    Event Mendatang
                </h3>
                <div class="overflow-x-auto">
                    <table class="event-table">
                        <colgroup>
                            <col class="col-name">
                            <col class="col-date">
                            <col class="col-loc">
                            <col class="col-status">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-left">Nama Event</th>
                                <th class="text-left">Tanggal</th>
                                <th class="text-left">Lokasi</th>
                                <th class="text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingEvents as $event)
                            <tr>
                                <td class="font-semibold text-gray-800 text-xs">{{ $event->judul }}</td>
                                <td class="text-gray-600 text-xs">
                                    {{ $event->tanggal_waktu_mulai->format('d M Y') }}
                                </td>
                                <td class="text-gray-600 text-xs">
                                    {{ Str::limit($event->lokasi, 18) }}
                                </td>
                                <td>
                                    @php
                                        $statusConfig = match($event->status) {
                                            'published' => ['class' => 'bg-emerald-100 text-emerald-700', 'dot' => 'bg-emerald-500'],
                                            'draft'     => ['class' => 'bg-gray-100 text-gray-500',      'dot' => 'bg-gray-400'],
                                            'finished'  => ['class' => 'bg-red-50 text-red-500',         'dot' => 'bg-red-400'],
                                            default     => ['class' => 'bg-blue-50 text-blue-700',       'dot' => 'bg-blue-600'],
                                        };
                                    @endphp
                                    <span class="badge {{ $statusConfig['class'] }}">
                                        <span class="badge-dot {{ $statusConfig['dot'] }}"></span>
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-400 text-xs">
                                    <i class="fa-regular fa-calendar-xmark text-xl block mb-1.5 text-gray-200"></i>
                                    Belum ada event mendatang
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Latest Reviews --}}
            <div class="panel">
                <h3 class="panel-title mb-3">
                    <span class="panel-title-icon"><i class="fa-solid fa-comments"></i></span>
                    Review Terbaru
                </h3>
                <div class="space-y-0.5 max-h-60 overflow-y-auto slim-scroll pr-1">
                    @forelse($latestReviews as $review)
                    <div class="review-item">
                        <div class="review-avatar">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="font-semibold text-xs text-gray-800 truncate">{{ $review->user->name }}</p>
                                <div class="flex gap-0.5 flex-shrink-0">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $review->rate ? 'solid' : 'regular' }} fa-star text-[9px]
                                           {{ $i <= $review->rate ? 'text-amber-400' : 'text-gray-200' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-[10px] font-semibold mt-0.5 brand-text flex items-center gap-1">
                                <i class="fa-solid fa-calendar-days text-[9px]"></i>
                                {{ $review->event->judul }}
                            </p>
                            <p class="text-[10px] text-gray-400 mt-0.5 leading-relaxed">{{ $review->review }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="py-6 text-center">
                        <i class="fa-regular fa-comment-dots text-2xl block mb-1.5 text-gray-200"></i>
                        <p class="text-gray-400 text-xs">Belum ada review masuk</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const BRAND      = '#1e3a8a';
        const BRAND_MID  = '#3b5fc0';
        const dbData     = @json($salesData);

        const labels = dbData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' });
        });
        const values = dbData.map(item => item.total);

        const ctx = document.getElementById('salesChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 208);
        gradient.addColorStop(0, 'rgba(30,58,138, 0.15)');
        gradient.addColorStop(1, 'rgba(30,58,138, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels.length > 0 ? labels : ['Sen','Sel','Rab','Kam','Jum','Sab','Min'],
                datasets: [{
                    label: 'Tiket',
                    data: values.length > 0 ? values : [0,0,0,0,0,0,0],
                    borderColor: BRAND,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: BRAND,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: BRAND,
                        padding: 9,
                        cornerRadius: 7,
                        titleFont: { size: 11 },
                        bodyFont:  { size: 11 },
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid:  { color: '#eef2ff' },
                        ticks: { font: { size: 10 }, color: '#9ca3af' }
                    },
                    x: {
                        grid:  { display: false },
                        ticks: { font: { size: 10 }, color: '#9ca3af' }
                    }
                }
            }
        });
    </script>
    @endpush
</x-layouts.admin>
