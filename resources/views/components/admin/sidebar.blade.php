<div class="drawer-side is-drawer-close:overflow-visible">
    <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>

    <div class="flex min-h-full flex-col bg-base-200
                w-64 is-drawer-close:w-14 is-drawer-open:w-80">

        <!-- Logo -->
        <div class="w-full flex items-center justify-center p-4">
            <img src="{{ asset('assets/images/logo_bengkod.svg') }}" alt="Logo" class="h-10">
        </div>

        <!-- Menu -->
        <ul class="menu w-full grow gap-1 px-2">

            <!-- Dashboard -->
            <li class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 rounded-lg' : '' }}">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3
                          is-drawer-close:tooltip is-drawer-close:tooltip-right"
                   data-tip="Dashboard">
                    <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l9-9l9 9M4.5 10.5V21h6v-6h3v6h6V10.5" />
                    </svg>
                    <span class="is-drawer-close:hidden">Dashboard</span>
                </a>
            </li>

            <!-- Kategori -->
            <li class="{{ request()->routeIs('admin.categories.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3
                          is-drawer-close:tooltip is-drawer-close:tooltip-right"
                   data-tip="Kategori">
                    <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                    </svg>
                    <span class="is-drawer-close:hidden">Manajemen Kategori</span>
                </a>
            </li>

            <!-- Event -->
            <li class="{{ request()->routeIs('admin.events.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                <a href="{{ route('admin.events.index') }}"
                   class="flex items-center gap-3
                          is-drawer-close:tooltip is-drawer-close:tooltip-right"
                   data-tip="Event">
                    <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    <span class="is-drawer-close:hidden">Manajemen Event</span>
                </a>
            </li>

            <!-- Tipe Tiket -->
            <li class="{{ request()->routeIs('admin.ticket-types.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                <a href="{{ route('admin.ticket-types.index') }}"
                   class="flex items-center gap-3
                          is-drawer-close:tooltip is-drawer-close:tooltip-right"
                   data-tip="Tipe Tiket">
                    <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Tipe Tiket</span>
                </a>
            </li>


             <!-- Location -->
            <li class="{{ request()->routeIs('admin.location.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                <a href="{{ route('admin.location.index') }}"
                   class="flex items-center gap-3
                          is-drawer-close:tooltip is-drawer-close:tooltip-right"
                   data-tip="Lokasi">
                    <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Manajemen Lokasi</span>
                </a>
            </li>

            <!-- History -->
            <li class="{{ request()->routeIs('admin.histories.*') ? 'bg-gray-200 rounded-lg' : '' }}">
                <a href="{{ route('admin.histories.index') }}"
                   class="flex items-center gap-3
                          is-drawer-close:tooltip is-drawer-close:tooltip-right"
                   data-tip="History">
                    <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="is-drawer-close:hidden">History Pembelian</span>
                </a>
            </li>

        </ul>

        <!-- Logout -->
        <div class="w-full p-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="btn btn-outline btn-error w-full
                               flex items-center gap-3
                               is-drawer-close:tooltip is-drawer-close:tooltip-right"
                        data-tip="Logout">
                    <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    <span class="is-drawer-close:hidden">Logout</span>
                </button>
            </form>
        </div>

    </div>
</div>