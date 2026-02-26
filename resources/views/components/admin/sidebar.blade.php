<div class="drawer-side is-drawer-close:overflow-visible ">
    <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
    <div class="flex min-h-full flex-col items-start bg-base-200 w-64 is-drawer-close:w-14 is-drawer-open:w-80">
        <div class="w-full flex items-center justify-center p-4">
            <img src="{{ asset('assets/images/logo_bengkod.svg') }}" alt="Logo">
        </div>

        <!-- Sidebar content here -->
        <ul class="menu w-full grow gap-1">
            <!-- Dashboard Item -->
            <li
                class="{{ request()->routeIs('superadmin.dashboard') || request()->routeIs('pengelola.dashboard') ? 'bg-gray-200 rounded-lg' : '' }}">
                <a href="{{ auth()->user()->role === 'superadmin' ? route('superadmin.dashboard') : route('pengelola.dashboard') }}"
                    class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Dashboard">
                    <!-- Home icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M6 19h3v-5q0-.425.288-.712T10 13h4q.425 0 .713.288T15 14v5h3v-9l-6-4.5L6 10zm-2 0v-9q0-.475.213-.9t.587-.7l6-4.5q.525-.4 1.2-.4t1.2.4l6 4.5q.375.275.588.7T20 10v9q0 .825-.588 1.413T18 21h-4q-.425 0-.712-.288T13 20v-5h-2v5q0 .425-.288.713T10 21H6q-.825 0-1.412-.587T4 19m8-6.75" />
                    </svg>
                    <span class="is-drawer-close:hidden">Dashboard</span>
                </a>
            </li>

            @if(auth()->user()->role === 'superadmin')
                <!-- Kategori item -->
                <li class="{{ request()->routeIs('superadmin.categories.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                    <a href="{{ route('superadmin.categories.index') }}"
                        class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Kategori">
                        <!-- icon Kategori -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4h6v6H4zm10 0h6v6h-6zM4 14h6v6H4zm10 3a3 3 0 1 0 6 0a3 3 0 1 0-6 0" />
                        </svg>
                        <span class="is-drawer-close:hidden">Manajemen Kategori</span>
                    </a>
                </li>

                <!-- Voucher item -->
                <li class="{{ request()->routeIs('superadmin.vouchers.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                    <a href="{{ route('superadmin.vouchers.index') }}"
                        class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Voucher">
                        <!-- icon Voucher -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/>
                            <line x1="13" y1="5" x2="13" y2="19" stroke-dasharray="4 4"/>
                        </svg>
                        <span class="is-drawer-close:hidden">Manajemen Voucher</span>
                    </a>
                </li>

            <!-- Payment Methods item -->
            <li class="{{ request()->routeIs('superadmin.payment-methods.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                <a href="{{ route('superadmin.payment-methods.index') }}"
                    class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Metode Pembayaran">
                    <!-- icon Payment -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m4 0h1M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Metode Pembayaran</span>
                </a>
            </li>

            <!-- User Management item -->
            <li class="{{ request()->routeIs('superadmin.users.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                <a href="{{ route('superadmin.users.index') }}"
                    class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="User Management">
                    <!-- icon Users -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0-8 0M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                    <span class="is-drawer-close:hidden">Manajemen User</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role === 'pengelola_event')

             <li class="{{ request()->routeIs('pengelola.reviews.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                <a href="{{ route('pengelola.reviews.index') }}"
                    class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Manajemen Review">
                    <!-- icon Review -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12h-8v2h8v-2zm0-3h-8v2h8V11zm0-3H4V6h14v2z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Manajemen Review</span>
                </a>
            </li>

                <!-- Event item -->
                <li class="{{ request()->routeIs('pengelola.events.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                    <a href="{{ route('pengelola.events.index') }}"
                        class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Manajemen Event">
                        <!-- icon Event -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3a2 2 0 0 0 0-4V7a2 2 0 0 1 2-2" />
                        </svg>
                        <span class="is-drawer-close:hidden">Manajemen Event</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->role === 'pengelola_event')
                <!-- Ticket Type Item -->
                <li class="{{ request()->routeIs('pengelola.ticket-types.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                    <a href="{{ route('pengelola.ticket-types.index') }}"
                        class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Tipe Tiket">
                        <!-- icon Ticket -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M21 9v2h-2v2h2v2h-2v2h2v4h-6v-2h-2v2h-2v-2h-2v2H5v-4h2v-2H5v-2h2V9H5V7h2V5H5V1h6v2h2V1h2v2h2V1h4v4h-2v2h2v2zm-4-4h-2V3h-2v2h-2V3h-2v2H7v14h4v-2h2v2h4v-2h-2v-2h2v-2h-2V9h2V7h-2V5z" />
                        </svg>
                        <span class="is-drawer-close:hidden">Tipe Tiket</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->role === 'superadmin')
                <!-- History item -->
                <li class="{{ request()->routeIs('superadmin.histories.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                    <a href="{{ route('superadmin.histories.index') }}"
                        class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="History">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span class="is-drawer-close:hidden">History Pembelian</span>
                    </a>
                </li>
            @endif
        </ul>

        <!-- logout -->
        <div class="w-full p-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="btn btn-outline btn-error w-full is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Logout">
                    <!-- Logout icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M10 17v-2h4v-2h-4v-2l-5 3l5 3m9-12H5q-.825 0-1.413.588T3 7v10q0 .825.587 1.413T5 19h14q.825 0 1.413-.587T21 17v-3h-2v3H5V7h14v3h2V7q0-.825-.587-1.413T19 5z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>
