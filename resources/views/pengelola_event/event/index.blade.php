<x-layouts.admin title="Manajemen Event">
    @if (session('success'))
    <div class="toast toast-bottom toast-center">
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

    <div class="container mx-auto p-10">
        <div class="flex">
            <h1 class="text-3xl font-semibold mb-4">Manajemen Event</h1>
            <a href="{{ route('pengelola.events.create') }}" class="btn btn-primary ml-auto">Tambah Event</a>
        </div>
        <div class="overflow-x-auto rounded-box bg-white p-5 shadow-xs">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="w-1/3">Judul</th>
                        <th>Status</th>
                        <th>Publish At</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $index => $event)
                    <tr>
                        <th>{{ $index + 1 }}</th>
                        <td>{{ $event->judul }}</td>
                        <td>
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
                            <span class="badge badge-sm {{ $badgeClass }}">{{ $statusText }}</span>
                        </td>
                        <td>{{ $event->publish_at ? \Carbon\Carbon::parse($event->publish_at)->format('d M Y H:i') : '-' }}</td>
                        <td>{{ $event->kategori->nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->tanggal_waktu_mulai)->format('d M Y') }}</td>
                        <td>{{ $event->lokasi }}</td>
                        <td>
                            <div class="dropdown dropdown-end">
                                <button class="btn btn-ghost btn-sm" tabindex="0">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8c1.1 0 2-1 2-2s-.9-2-2-2-2 1-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </button>
                                <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box shadow w-52 border">
                                    <li>
                                        <a href="{{ route('pengelola.events.show', $event->id) }}" class="text-blue-600">Detail</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('pengelola.events.edit', $event->id) }}" class="text-blue-600">Edit</a>
                                    </li>
                                    <li>
                                        <a onclick="openDeleteModal(this)" data-id="{{ $event->id }}" class="text-red-600 cursor-pointer">Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada event tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Modal -->
    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')

            <input type="hidden" name="event_id" id="delete_event_id">

            <h3 class="text-lg font-bold mb-4">Hapus Event</h3>
            <p>Apakah Anda yakin ingin menghapus event ini?</p>
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
            document.getElementById("delete_event_id").value = id;
            form.action = `/pengelola/events/${id}`;
            delete_modal.showModal();
        }
    </script>

</x-layouts.admin>