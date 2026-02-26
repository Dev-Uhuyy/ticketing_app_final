<x-layouts.admin title="Manajemen Metode Pembayaran">
   
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
            <h1 class="text-3xl font-semibold mb-4">Manajemen Metode Pembayaran</h1>
            <button class="btn btn-primary ml-auto" onclick="add_modal.showModal()">
                Tambah Metode Pembayaran
            </button>
        </div>
        <div class="overflow-x-auto rounded-box bg-white p-5 shadow-xs">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Metode Pembayaran</th>
                        <th class="text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paymentMethods as $index => $method)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $method->name }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary mr-2" onclick="openEditModal({{ $method->id }}, '{{ $method->name }}')" type="button">Edit</button>
                                <form action="{{ route('superadmin.payment-methods.destroy', $method->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm bg-red-500 text-white" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada metode pembayaran tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <dialog id="add_modal" class="modal">
        <div class="modal-box w-11/12 max-w-md">
            <form method="dialog">
                <button type="submit" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">Tambah Metode Pembayaran</h3>
            <form action="{{ route('superadmin.payment-methods.store') }}" method="POST">
                @csrf
                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text">Metode Pembayaran <span class="text-red-500">*</span></span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}"
                        placeholder="Contoh: Bank Transfer" 
                        class="input input-bordered w-full @error('name') input-error @enderror"
                        required
                    />
                    @error('name')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="flex gap-2 justify-end">
                    <button type="button" class="btn btn-ghost" onclick="add_modal.close()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Edit Modal -->
    <dialog id="edit_modal" class="modal">
        <div class="modal-box w-11/12 max-w-md">
            <form method="dialog">
                <button type="submit" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">Edit Metode Pembayaran</h3>
            <form id="edit_form" method="POST">
                @csrf
                @method('PUT')
                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text">Metode Pembayaran <span class="text-red-500">*</span></span>
                    </label>
                    <input 
                        type="text" 
                        id="edit_name"
                        name="name" 
                        placeholder="Contoh: Bank Transfer" 
                        class="input input-bordered w-full"
                        required
                    />
                </div>

                <div class="flex gap-2 justify-end">
                    <button type="button" class="btn btn-ghost" onclick="edit_modal.close()">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function openEditModal(id, name) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_form').action = `/admin/payment-methods/${id}`;
            edit_modal.showModal();
        }
    </script>

</x-layouts.admin>
