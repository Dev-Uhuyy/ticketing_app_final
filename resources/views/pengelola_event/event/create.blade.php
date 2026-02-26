<x-layouts.admin title="Tambah Event Baru">
    @if ($errors->any())
        <div class="toast toast-bottom toast-center z-50">
            <ul class="alert alert-error">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <script>
            setTimeout(() => {
                document.querySelector('.toast')?.remove()
            }, 5000)
        </script>
    @endif

    <div class="container mx-auto p-10">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Tambah Event Baru</h2>

                <form id="eventForm" class="space-y-4" method="post" action="{{ route('pengelola.events.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="action" id="formAction" value="draft">

                    <!-- Judul Event -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Judul Event</span>
                        </label>
                        <input
                            type="text"
                            name="judul"
                            placeholder="Contoh: Konser Musik Rock"
                            class="input input-bordered w-full"
                            value="{{ old('judul') }}" />
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Deskripsi</span>
                        </label>
                        <textarea
                            name="deskripsi"
                            placeholder="Deskripsi lengkap tentang event..."
                            class="textarea textarea-bordered h-24 w-full">{{ old('deskripsi') }}</textarea>
                    </div>

                    <!-- Tanggal & Waktu Mulai -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Tanggal & Waktu Mulai</span>
                        </label>
                        <input
                            type="datetime-local"
                            name="tanggal_waktu_mulai"
                            class="input input-bordered w-full"
                            value="{{ old('tanggal_waktu_mulai') }}" />
                    </div>

                    <!-- Tanggal & Waktu Selesai -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Tanggal & Waktu Selesai</span>
                        </label>
                        <input
                            type="datetime-local"
                            name="tanggal_waktu_selesai"
                            class="input input-bordered w-full"
                            value="{{ old('tanggal_waktu_selesai') }}" />
                    </div>

                    <!-- Lokasi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Lokasi</span>
                        </label>
                        <input
                            type="text"
                            name="lokasi"
                            placeholder="Contoh: Stadion Utama"
                            class="input input-bordered w-full"
                            value="{{ old('lokasi') }}" />
                    </div>

                    <!-- Kategori -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Kategori</span>
                        </label>
                        <select name="kategori_id" class="select select-bordered w-full">
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('kategori_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Upload Gambar -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Gambar Event</span>
                        </label>
                        <input
                            type="file"
                            name="gambar"
                            accept="image/*"
                            class="file-input file-input-bordered w-full"
                            id="gambarInput" />
                        <label class="label">
                            <span class="label-text-alt">Format: JPG, PNG, max 5MB</span>
                        </label>
                    </div>

                    <!-- Preview Gambar -->
                    <div id="imagePreview" class="hidden overflow-hidden">
                        <label class="label">
                            <span class="label-text font-semibold">Preview Gambar</span>
                        </label>
                        <div class="avatar max-w-sm">
                            <div class="w-full rounded-lg">
                                <img id="previewImg" src="" alt="Preview">
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal Publikasi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Jadwal Publikasi <span class="text-gray-400 font-normal">(opsional)</span></span>
                        </label>
                        <input
                            type="datetime-local"
                            name="publish_at"
                            class="input input-bordered w-full"
                            value="{{ old('publish_at') }}" />
                        <label class="label">
                            <span class="label-text-alt">Kosongkan untuk publish langsung, isi untuk jadwalkan publikasi otomatis</span>
                        </label>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="card-actions justify-end mt-6 gap-2">
                        <button type="reset" class="btn btn-ghost">Reset</button>
                        <button type="button" onclick="submitForm('draft')" class="btn btn-outline">Save as Draft</button>
                        <button type="button" onclick="submitForm('publish')" class="btn btn-primary">Publish</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('eventForm');
        const gambarInput = document.getElementById('gambarInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        function submitForm(action) {
            document.getElementById('formAction').value = action;
            form.submit();
        }

        gambarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        form.addEventListener('reset', function() {
            imagePreview.classList.add('hidden');
        });
    </script>
</x-layouts.admin>