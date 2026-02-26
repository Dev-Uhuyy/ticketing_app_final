<x-layouts.admin title="Detail Event">
    <div class="container mx-auto p-10">
        @if (session('success'))
            <div class="toast toast-bottom toast-center z-50">
                <div class="alert alert-success">
                    <span>{{ session('success') }}</span>
                </div>
            </div>

            <script>
                setTimeout(() => {
                    document.querySelector('.toast')?.remove()
                }, 3000)
            </script>
        @endif

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="flex items-center gap-4 mb-6">
                    <h2 class="card-title text-2xl">Detail Event</h2>
                    @php
                        $badgeClass = match($event->status) {
                            'draft'     => 'bg-gray-400 text-white',
                            'scheduled' => 'bg-yellow-400 text-white',
                            'published' => 'bg-green-500 text-white',
                            'on_going'  => 'bg-blue-500 text-white',
                            'finished'  => 'bg-red-800 text-white',
                            default     => 'bg-gray-300 text-white',
                        };
                        $statusText = match($event->status) {
                            'draft'     => 'Draft',
                            'scheduled' => 'Scheduled',
                            'published' => 'Published',
                            'on_going'  => 'On Going',
                            'finished'  => 'Finished',
                            default     => ucfirst($event->status),
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                </div>

                <!-- Status & Publish At Info -->
                @if ($event->publish_at)
                <div class="alert alert-info mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>
                        @if ($event->status === 'scheduled')
                            Dijadwalkan publish pada: <strong>{{ \Carbon\Carbon::parse($event->publish_at)->format('d M Y, H:i') }}</strong>
                        @else
                            Dipublikasikan pada: <strong>{{ \Carbon\Carbon::parse($event->publish_at)->format('d M Y, H:i') }}</strong>
                        @endif
                    </span>
                </div>
                @endif

                <form id="eventForm" class="space-y-4" method="post"
                    action="{{ route('pengelola.events.update', $event->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Judul Event -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Judul Event</span>
                        </label>
                        <input type="text" name="judul"
                            class="input input-bordered w-full"
                            value="{{ $event->judul }}" disabled />
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Deskripsi</span>
                        </label>
                        <textarea name="deskripsi"
                            class="textarea textarea-bordered h-24 w-full"
                            disabled>{{ $event->deskripsi }}</textarea>
                    </div>

                    <!-- Tanggal & Waktu Mulai -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Tanggal & Waktu Mulai</span>
                        </label>
                        <input type="datetime-local" name="tanggal_waktu_mulai"
                            class="input input-bordered w-full"
                            value="{{ $event->tanggal_waktu_mulai ? \Carbon\Carbon::parse($event->tanggal_waktu_mulai)->format('Y-m-d\TH:i') : '' }}"
                            disabled />
                    </div>

                    <!-- Tanggal & Waktu Selesai -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Tanggal & Waktu Selesai</span>
                        </label>
                        <input type="datetime-local" name="tanggal_waktu_selesai"
                            class="input input-bordered w-full"
                            value="{{ $event->tanggal_waktu_selesai ? \Carbon\Carbon::parse($event->tanggal_waktu_selesai)->format('Y-m-d\TH:i') : '' }}"
                            disabled />
                    </div>

                    <!-- Lokasi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Lokasi</span>
                        </label>
                        <input type="text" name="lokasi"
                            class="input input-bordered w-full"
                            value="{{ $event->lokasi }}" disabled />
                    </div>

                    <!-- Kategori -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Kategori</span>
                        </label>
                        <select name="kategori_id" class="select select-bordered w-full" disabled>
                            <option value="" disabled>Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $category->id == $event->kategori_id ? 'selected' : '' }}>
                                    {{ $category->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Preview Gambar -->
                    <div id="imagePreview" class="overflow-hidden {{ $event->gambar ? '' : 'hidden' }}">
                        <label class="label">
                            <span class="label-text font-semibold">Gambar Event</span>
                        </label>
                        <div class="avatar max-w-sm">
                            <div class="w-full rounded-lg">
                                @if ($event->gambar)
                                    <img src="{{ asset('images/events/' . $event->gambar) }}" alt="Preview">
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- List Ticket -->
        <div class="mt-10">
            <div class="flex">
                <h1 class="text-3xl font-semibold mb-4">List Ticket</h1>
                <button onclick="add_ticket_modal.showModal()" class="btn btn-primary ml-auto">Tambah Ticket</button>
            </div>
            <div class="overflow-x-auto rounded-box bg-white p-5 shadow-xs">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="w-1/3">Tipe</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $index => $ticket)
                            <tr>
                                <th>{{ $index + 1 }}</th>
                                <td>{{ $ticket->tipe }}</td>
                                <td>{{ $ticket->harga }}</td>
                                <td>{{ $ticket->stok }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary mr-2" onclick="openEditModal(this)"
                                        data-id="{{ $ticket->id }}" data-tipe="{{ $ticket->tipe }}"
                                        data-harga="{{ $ticket->harga }}" data-stok="{{ $ticket->stok }}">Edit</button>
                                    <button class="btn btn-sm bg-red-500 text-white" onclick="openDeleteModal(this)"
                                        data-id="{{ $ticket->id }}">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada ticket tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Ticket Modal -->
    <dialog id="add_ticket_modal" class="modal">
        <form method="POST" action="{{ route('pengelola.tickets.store') }}" class="modal-box">
            @csrf
            <h3 class="text-lg font-bold mb-4">Tambah Ticket</h3>
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Tipe Ticket</span>
                </label>
                <select name="tipe" class="select select-bordered w-full" required>
                    <option value="" disabled selected>Pilih Tipe Ticket</option>
                    @foreach ($ticketTypes as $type)
                        <option value="{{ $type->name }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Harga</span>
                </label>
                <input type="number" name="harga" placeholder="Contoh: 50000" class="input input-bordered w-full" required />
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Stok</span>
                </label>
                <input type="number" name="stok" placeholder="Contoh: 100" class="input input-bordered w-full" required />
            </div>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Tambah</button>
                <button class="btn" onclick="add_ticket_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <!-- Edit Ticket Modal -->
    <dialog id="edit_ticket_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('PUT')
            <input type="hidden" name="ticket_id" id="edit_ticket_id">
            <h3 class="text-lg font-bold mb-4">Edit Ticket</h3>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Tipe Ticket</span>
                </label>
                <select name="tipe" id="edit_tipe" class="select select-bordered w-full" required>
                    <option value="" disabled selected>Pilih Tipe Ticket</option>
                    @foreach ($ticketTypes as $type)
                        <option value="{{ $type->name }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Harga</span>
                </label>
                <input type="number" name="harga" id="edit_harga" placeholder="Contoh: 50000" class="input input-bordered w-full" required />
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Stok</span>
                </label>
                <input type="number" name="stok" id="edit_stok" placeholder="Contoh: 100" class="input input-bordered w-full" required />
            </div>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" onclick="edit_ticket_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <!-- Delete Ticket Modal -->
    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')
            <input type="hidden" name="ticket_id" id="delete_ticket_id">
            <h3 class="text-lg font-bold mb-4">Hapus Ticket</h3>
            <p>Apakah Anda yakin ingin menghapus ticket ini?</p>
            <div class="modal-action">
                <button class="btn btn-error text-white" type="submit">Hapus</button>
                <button class="btn" onclick="delete_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <script>
        function openDeleteModal(button) {
            const id = button.dataset.id;
            const form = document.querySelector('#delete_modal form');
            document.getElementById("delete_ticket_id").value = id;
            form.action = `/pengelola/tickets/${id}`;
            delete_modal.showModal();
        }

        function openEditModal(button) {
            const id = button.dataset.id;
            const tipe = button.dataset.tipe;
            const harga = button.dataset.harga;
            const stok = button.dataset.stok;

            const form = document.querySelector('#edit_ticket_modal form');
            document.getElementById("edit_ticket_id").value = id;
            document.getElementById("edit_tipe").value = tipe;
            document.getElementById("edit_harga").value = harga;
            document.getElementById("edit_stok").value = stok;
            form.action = `/pengelola/tickets/${id}`;
            edit_ticket_modal.showModal();
        }
    </script>
</x-layouts.admin>