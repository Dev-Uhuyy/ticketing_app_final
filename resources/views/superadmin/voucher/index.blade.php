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

    <!-- Voucher Table -->
    <div class="container mx-auto p-10">
        <div class="flex">
            <h1 class="text-3xl font-semibold mb-4">Manajemen Voucher</h1>
            <button class="btn btn-primary ml-auto" onclick="add_modal.showModal()">Tambah Voucher</button>
        </div>
        <div class="overflow-x-auto rounded-box bg-white p-5 shadow-xs">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="w-2/12">Kode</th>
                        <th>Diskon</th>
                        <th>Penggunaan</th>
                        <th>Tanggal Mulai</th> 
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
                                {{ $voucher->diskon }} {{ $voucher->tipe_diskon === 'percent' ? '%' : 'IDR' }}
                            </td>
                            <td>{{ $voucher->jumlah_digunakan }} / {{ $voucher->penggunaan_maksimal ?? '∞' }}</td>
                            
                            <td>
                                @if ($voucher->tanggal_mulai)
                                    {{ $voucher->tanggal_mulai->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            
                            <td>
                                @if ($voucher->tanggal_kadaluarsa)
                                    {{ $voucher->tanggal_kadaluarsa->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            
                            <td>
                                <input type="checkbox" class="toggle toggle-primary"
                                    onclick="ubahStatusVoucher({{ $voucher->id }}, this)"
                                    {{ $voucher->aktif ? 'checked' : '' }} />
                            </td>
                            
                            <td>
                                <button class="btn btn-sm btn-primary mr-2" 
                                    onclick="openEditModal(this)" 
                                    data-id="{{ $voucher->id }}" 
                                    data-code="{{ $voucher->code }}" 
                                    data-deskripsi="{{ $voucher->deskripsi }}" 
                                    data-diskon="{{ $voucher->diskon }}" 
                                    data-tipe_diskon="{{ $voucher->tipe_diskon }}" 
                                    data-penggunaan_maksimal="{{ $voucher->penggunaan_maksimal }}" 
                                    data-tanggal_mulai="{{ $voucher->tanggal_mulai?->format('Y-m-d') }}"
                                    data-tanggal_kadaluarsa="{{ $voucher->tanggal_kadaluarsa?->format('Y-m-d') }}" 
                                    data-aktif="{{ $voucher->aktif ? 'true' : 'false' }}">
                                    Edit
                                </button>
                                <button class="btn btn-sm bg-red-500 text-white" onclick="openDeleteModal(this)" data-id="{{ $voucher->id }}">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada voucher tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Voucher Modal -->
    <dialog id="add_modal" class="modal">
        <form method="POST" action="{{ route('superadmin.vouchers.store') }}" class="modal-box max-w-md max-h-[85vh]">
            @csrf
            <h3 class="text-lg font-bold mb-4">Tambah Voucher</h3>
            
            <div class="form-control w-full mb-4">
                <label class="label mb-2"><span class="label-text">Kode Voucher</span></label>
                <input type="text" class="input input-bordered w-full" name="code" required />
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2"><span class="label-text">Deskripsi</span></label>
                <textarea class="textarea textarea-bordered w-full" name="deskripsi"></textarea>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2"><span class="label-text">Tipe Diskon</span></label>
                <select class="select select-bordered w-full" id="add_tipe_diskon" name="tipe_diskon" required onchange="aturBatasDiskon(this, 'add_diskon'); validasiDiskon('add_diskon', 'add_tipe_diskon', 'add_error_diskon')">
                    <option value="">Pilih Tipe Diskon</option>
                    <option value="fixed">Fixed (IDR)</option>
                    <option value="percent">Percentage (%)</option>
                </select>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2"><span class="label-text">Jumlah Diskon</span></label>
                <input type="number" step="0.01" min="0" id="add_diskon" class="input input-bordered w-full" name="diskon" required oninput="validasiDiskon('add_diskon', 'add_tipe_diskon', 'add_error_diskon')" />
                <span id="add_error_diskon" class="text-error text-xs mt-1 hidden"></span>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2"><span class="label-text">Jumlah Diskon</span></label>
                <input type="number" step="0.01" min="0" id="add_diskon" class="input input-bordered w-full" name="diskon" required />
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2"><span class="label-text">Penggunaan Maksimal</span></label>
                <input type="number" min="1" class="input input-bordered w-full" name="penggunaan_maksimal" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-control w-full mb-4">
                    <label class="label mb-2"><span class="label-text">Tanggal Mulai</span></label>
                    <input type="date" class="input input-bordered w-full" name="tanggal_mulai" id="add_tanggal_mulai" onchange="aturMinKadaluarsa('add_tanggal_mulai', 'add_tanggal_kadaluarsa', 'add_error_tanggal')" />
                </div>
                <div class="form-control w-full mb-4">
                    <label class="label mb-2"><span class="label-text">Tanggal Kadaluarsa</span></label>
                    <input type="date" class="input input-bordered w-full" name="tanggal_kadaluarsa" id="add_tanggal_kadaluarsa" />
                    <span id="add_error_tanggal" class="text-error text-xs mt-1 hidden">Tidak boleh mendahului tanggal mulai!</span>
                </div>
            </div>

            <div class="form-control mb-4">
                <label class="cursor-pointer label">
                    <span class="label-text">Aktifkan Voucher</span>
                    <input type="checkbox" name="aktif" value="1" class="toggle toggle-primary" checked />
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
        <form method="POST" class="modal-box max-w-md max-h-[85vh]">
            @csrf
            @method('PUT')
            <h3 class="text-lg font-bold mb-4">Edit Voucher</h3>

            <div class="form-control w-full mb-4">
                <label class="label mb-2"><span class="label-text">Kode Voucher</span></label>
                <input type="text" class="input input-bordered w-full" id="edit_voucher_code" name="code" required />
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2"><span class="label-text">Deskripsi</span></label>
                <textarea class="textarea textarea-bordered w-full" id="edit_voucher_deskripsi" name="deskripsi"></textarea>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2"><span class="label-text">Tipe Diskon</span></label>
                <select class="select select-bordered w-full" id="edit_voucher_tipe_diskon" name="tipe_diskon" required onchange="aturBatasDiskon(this, 'edit_voucher_diskon'); validasiDiskon('edit_voucher_diskon', 'edit_voucher_tipe_diskon', 'edit_error_diskon')">
                    <option value="fixed">Fixed (IDR)</option>
                    <option value="percent">Percentage (%)</option>
                </select>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2"><span class="label-text">Jumlah Diskon</span></label>
                <input type="number" step="0.01" min="0" class="input input-bordered w-full" id="edit_voucher_diskon" name="diskon" required oninput="validasiDiskon('edit_voucher_diskon', 'edit_voucher_tipe_diskon', 'edit_error_diskon')" />
                <span id="edit_error_diskon" class="text-error text-xs mt-1 hidden"></span>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2"><span class="label-text">Jumlah Diskon</span></label>
                <input type="number" step="0.01" min="0" class="input input-bordered w-full" id="edit_voucher_diskon" name="diskon" required />
            </div>

            <div class="form-control w-full mb-4">
                <label class="label mb-2"><span class="label-text">Penggunaan Maksimal</span></label>
                <input type="number" min="1" class="input input-bordered w-full" id="edit_voucher_penggunaan" name="penggunaan_maksimal" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-control w-full mb-4">
                    <label class="label mb-2"><span class="label-text">Tanggal Mulai</span></label>
                    <input type="date" class="input input-bordered w-full" id="edit_voucher_tanggal_mulai" name="tanggal_mulai" onchange="aturMinKadaluarsa('edit_voucher_tanggal_mulai', 'edit_voucher_tanggal_kadaluarsa', 'edit_error_tanggal')" />
                </div>
                <div class="form-control w-full mb-4">
                    <label class="label mb-2"><span class="label-text">Tanggal Kadaluarsa</span></label>
                    <input type="date" class="input input-bordered w-full" id="edit_voucher_tanggal_kadaluarsa" name="tanggal_kadaluarsa" />
                    <span id="edit_error_tanggal" class="text-error text-xs mt-1 hidden">Tidak boleh mendahului tanggal mulai!</span>
                </div>
            </div>

            <div class="form-control mb-4">
                <label class="cursor-pointer label">
                    <span class="label-text">Aktifkan Voucher</span>
                    <input type="hidden" name="aktif" value="0">
                    <input type="checkbox" name="aktif" value="1" id="edit_voucher_aktif" class="toggle toggle-primary" />
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

    <!-- Voucher Script -->
    <script>
        function openEditModal(button) {
            const { id, code, deskripsi, diskon, tipe_diskon, penggunaan_maksimal, tanggal_mulai, tanggal_kadaluarsa, aktif } = button.dataset;

            document.getElementById('edit_voucher_code').value = code;
            document.getElementById('edit_voucher_deskripsi').value = deskripsi || '';
            document.getElementById('edit_voucher_diskon').value = diskon;
            document.getElementById('edit_voucher_tipe_diskon').value = tipe_diskon;
            document.getElementById('edit_voucher_penggunaan').value = penggunaan_maksimal || '';
            document.getElementById('edit_voucher_tanggal_mulai').value = tanggal_mulai || '';
            document.getElementById('edit_voucher_tanggal_kadaluarsa').value = tanggal_kadaluarsa || '';
            document.getElementById('edit_voucher_aktif').checked = aktif === 'true';

            aturBatasDiskon(document.getElementById('edit_voucher_tipe_diskon'), 'edit_voucher_diskon');
            aturMinKadaluarsa('edit_voucher_tanggal_mulai', 'edit_voucher_tanggal_kadaluarsa');

            document.querySelector('#edit_modal form').action = `{{ url('admin/vouchers') }}/${id}`;
            edit_modal.showModal();
        }

        function openDeleteModal(button) {
            const { id } = button.dataset;
            const codeElement = button.parentElement.parentElement.querySelector('td:nth-child(2)');
            document.getElementById('delete_voucher_code').textContent = codeElement.textContent.trim();
            document.querySelector('#delete_modal form').action = `{{ url('admin/vouchers') }}/${id}`;
            delete_modal.showModal();
        }

        function ubahStatusVoucher(id, element) {
            const isAktif = element.checked ? 1 : 0;

            fetch(`{{ url('admin/vouchers') }}/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ aktif: isAktif })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const editBtn = element.closest('tr').querySelector('button[onclick^="openEditModal"]');
                    if (editBtn) {
                        editBtn.dataset.aktif = element.checked ? 'true' : 'false';
                    }
                } else {
                    element.checked = !element.checked;
                    alert('Gagal mengubah status di server!');
                }
            })
            .catch(() => {
                element.checked = !element.checked;
                alert('Terjadi kesalahan jaringan.');
            });
        }

        function aturBatasDiskon(selectElement, inputId) {
            const diskonInput = document.getElementById(inputId);
            if (selectElement.value === 'percent') {
                diskonInput.max = '100';
                diskonInput.placeholder = 'Maksimal 100%';
            } else {
                diskonInput.removeAttribute('max');
                diskonInput.placeholder = 'Masukkan nominal';
            }
        }

        function aturMinKadaluarsa(mulaiId, kadaluarsaId) {
            const inputMulai = document.getElementById(mulaiId);
            const inputKadaluarsa = document.getElementById(kadaluarsaId);
            
            if(inputMulai.value) {
                inputKadaluarsa.min = inputMulai.value;
            }

            if (inputKadaluarsa.value && inputKadaluarsa.value < inputMulai.value) {
                inputKadaluarsa.value = '';
                alert('Tanggal kadaluarsa tidak boleh lebih awal dari tanggal mulai!');
            }
        }

        function aturMinKadaluarsa(mulaiId, kadaluarsaId, errorId) {
            const inputMulai = document.getElementById(mulaiId);
            const inputKadaluarsa = document.getElementById(kadaluarsaId);
            // Tangkap elemen teks error-nya
            const errorText = document.getElementById(errorId);
            
            if(inputMulai.value) {
                inputKadaluarsa.min = inputMulai.value;
            }

            if (inputKadaluarsa.value && inputKadaluarsa.value < inputMulai.value) {
                inputKadaluarsa.value = ''; 
                
                inputKadaluarsa.classList.add('input-error'); 
                if (errorText) errorText.classList.remove('hidden'); 

            }
        }

        function validasiDiskon(inputId, selectId, errorId) {
            const inputEl = document.getElementById(inputId);
            const selectEl = document.getElementById(selectId);
            const errorEl = document.getElementById(errorId);
            
            const val = parseFloat(inputEl.value);
            const tipe = selectEl.value;
            let errorMsg = '';

            if (inputEl.value === '' || isNaN(val)) {
                errorMsg = '';
            } else if (tipe === 'percent' && (val < 0 || val > 100)) {
                errorMsg = 'Persentase diskon harus antara 0 - 100.';
            } else if (tipe === 'fixed' && val < 0) {
                errorMsg = 'Nominal diskon tidak boleh minus.';
            }

            if (errorMsg) {
                inputEl.classList.add('input-error');
                errorEl.textContent = errorMsg;
                errorEl.classList.remove('hidden');
            } else {
                inputEl.classList.remove('input-error');
                errorEl.classList.add('hidden');
            }
        }
    </script>
</x-layouts.admin>