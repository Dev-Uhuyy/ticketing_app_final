<x-layouts.admin title="Manajemen Voucher">

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
            <h1 class="text-3xl font-semibold mb-4">Manajemen Voucher</h1>
            <button class="btn btn-primary ml-auto" onclick="add_modal.showModal()">Tambah Voucher</button>
        </div>
        <div class="overflow-x-auto rounded-box bg-white p-5 shadow-xs">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="w-2/12">Kode</th>
                        <th>Diskon</th>
                        <th>Penggunaan</th>
                        <th>Kadaluarsa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vouchers as $index => $voucher)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td><span class="badge badge-primary">{{ $voucher->code }}</span></td>
                            <td>
                                {{ $voucher->diskon }}
                                @if ($voucher->tipe_diskon === 'percent')
                                    %
                                @else
                                    IDR
                                @endif
                            </td>
                            <td>{{ $voucher->jumlah_digunakan }} / {{ $voucher->penggunaan_maksimal ?? '∞' }}</td>
                            <td>
                                @if ($voucher->tanggal_kadaluarsa)
                                    {{ $voucher->tanggal_kadaluarsa->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($voucher->aktif)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-ghost">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary mr-2" onclick="openEditModal(this)" data-id="{{ $voucher->id }}" data-code="{{ $voucher->code }}" data-deskripsi="{{ $voucher->deskripsi }}" data-diskon="{{ $voucher->diskon }}" data-tipe_diskon="{{ $voucher->tipe_diskon }}" data-penggunaan_maksimal="{{ $voucher->penggunaan_maksimal }}" data-tanggal_kadaluarsa="{{ $voucher->tanggal_kadaluarsa?->format('Y-m-d') }}" data-aktif="{{ $voucher->aktif ? 'true' : 'false' }}">Edit</button>
                                <button class="btn btn-sm bg-red-500 text-white" onclick="openDeleteModal(this)" data-id="{{ $voucher->id }}">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada voucher tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Voucher Modal -->
    <dialog id="add_modal" class="modal">
        <form method="POST" action="/admin/vouchers" class="modal-box">
            @csrf
            <h3 class="text-lg font-bold mb-4">Tambah Voucher</h3>
            
            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Kode Voucher</span>
                </label>
                <input type="text" placeholder="Masukkan kode voucher" class="input input-bordered w-full" name="code" required />
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Deskripsi</span>
                </label>
                <textarea placeholder="Deskripsi voucher (opsional)" class="textarea textarea-bordered w-full" name="deskripsi"></textarea>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Jumlah Diskon</span>
                </label>
                <input type="number" step="0.01" placeholder="Masukkan jumlah diskon" class="input input-bordered w-full" name="diskon" required />
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Tipe Diskon</span>
                </label>
                <select class="select select-bordered w-full" name="tipe_diskon" required>
                    <option value="">Pilih Tipe Diskon</option>
                    <option value="fixed">Fixed (IDR)</option>
                    <option value="percent">Percentage (%)</option>
                </select>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Penggunaan Maksimal</span>
                </label>
                <input type="number" placeholder="Kosongkan untuk unlimited" class="input input-bordered w-full" name="penggunaan_maksimal" />
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Tanggal Kadaluarsa</span>
                </label>
                <input type="date" class="input input-bordered w-full" name="tanggal_kadaluarsa" />
            </div>

            <div class="form-control mb-4">
                <label class="cursor-pointer label">
                    <span class="label-text">Aktifkan Voucher</span>
                    <input type="checkbox" name="aktif" value="1" class="checkbox checkbox-primary" checked />
                </label>
            </div>

            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" onclick="add_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <!-- Edit Voucher Modal -->
    <dialog id="edit_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('PUT')

            <h3 class="text-lg font-bold mb-4">Edit Voucher</h3>

            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Kode Voucher</span>
                </label>
                <input type="text" placeholder="Masukkan kode voucher" class="input input-bordered w-full" id="edit_voucher_code" name="code" required />
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Deskripsi</span>
                </label>
                <textarea placeholder="Deskripsi voucher (opsional)" class="textarea textarea-bordered w-full" id="edit_voucher_deskripsi" name="deskripsi"></textarea>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Jumlah Diskon</span>
                </label>
                <input type="number" step="0.01" placeholder="Masukkan jumlah diskon" class="input input-bordered w-full" id="edit_voucher_diskon" name="diskon" required />
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Tipe Diskon</span>
                </label>
                <select class="select select-bordered w-full" id="edit_voucher_tipe_diskon" name="tipe_diskon" required>
                    <option value="fixed">Fixed (IDR)</option>
                    <option value="percent">Percentage (%)</option>
                </select>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Penggunaan Maksimal</span>
                </label>
                <input type="number" placeholder="Kosongkan untuk unlimited" class="input input-bordered w-full" id="edit_voucher_penggunaan" name="penggunaan_maksimal" />
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Tanggal Kadaluarsa</span>
                </label>
                <input type="date" class="input input-bordered w-full" id="edit_voucher_tanggal" name="tanggal_kadaluarsa" />
            </div>

            <div class="form-control mb-4">
                <label class="cursor-pointer label">
                    <span class="label-text">Aktifkan Voucher</span>
                    <input type="checkbox" name="aktif" value="1" id="edit_voucher_aktif" class="checkbox checkbox-primary" />
                </label>
            </div>

            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" onclick="edit_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <!-- Delete Modal -->
    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')

            <h3 class="text-lg font-bold mb-4">Hapus Voucher</h3>
            <p>Apakah Anda yakin ingin menghapus voucher <strong id="delete_voucher_code"></strong>?</p>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Hapus</button>
                <button class="btn" onclick="delete_modal.close()" type="button">Batal</button>
            </div>
        </form>
    </dialog>

    <script>
        function openEditModal(button) {
            const id = button.dataset.id;
            const code = button.dataset.code;
            const deskripsi = button.dataset.deskripsi;
            const diskon = button.dataset.diskon;
            const tipeDiskon = button.dataset.tipe_diskon;
            const penggunaanMaksimal = button.dataset.penggunaan_maksimal;
            const tanggalKadaluarsa = button.dataset.tanggal_kadaluarsa;
            const aktif = button.dataset.aktif === 'true';
            const form = document.querySelector('#edit_modal form');

            document.getElementById('edit_voucher_code').value = code;
            document.getElementById('edit_voucher_deskripsi').value = deskripsi || '';
            document.getElementById('edit_voucher_diskon').value = diskon;
            document.getElementById('edit_voucher_tipe_diskon').value = tipeDiskon;
            document.getElementById('edit_voucher_penggunaan').value = penggunaanMaksimal || '';
            document.getElementById('edit_voucher_tanggal').value = tanggalKadaluarsa || '';
            document.getElementById('edit_voucher_aktif').checked = aktif;

            form.action = `/admin/vouchers/${id}`;

            edit_modal.showModal();
        }

        function openDeleteModal(button) {
            const id = button.dataset.id;
            const form = document.querySelector('#delete_modal form');
            const codeElement = button.parentElement.parentElement.querySelector('td:nth-child(2)');
            const code = codeElement.textContent.trim();

            document.getElementById('delete_voucher_code').textContent = code;

            form.action = `/admin/vouchers/${id}`;

            delete_modal.showModal();
        }
    </script>
</x-layouts.admin>