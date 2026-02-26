<x-layouts.admin title="Edit Event">
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
                <h2 class="card-title text-2xl mb-6">Edit Event</h2>

                <form id="eventForm" class="space-y-4" method="post"
                    action="{{ route('pengelola.events.update', $event->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                            value="{{ old('judul', $event->judul) }}" />
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Deskripsi</span>
                        </label>
                        <textarea
                            name="deskripsi"
                            placeholder="Deskripsi lengkap tentang event..."
                            class="textarea textarea-bordered h-24 w-full">{{ old('deskripsi', $event->deskripsi) }}</textarea>
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
                            value="{{ old('tanggal_waktu_mulai', $event->tanggal_waktu_mulai ? \Carbon\Carbon::parse($event->tanggal_waktu_mulai)->format('Y-m-d\TH:i') : '') }}" />
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
                            value="{{ old('tanggal_waktu_selesai', $event->tanggal_waktu_selesai ? \Carbon\Carbon::parse($event->tanggal_waktu_selesai)->format('Y-m-d\TH:i') : '') }}" />
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
                            value="{{ old('lokasi', $event->lokasi) }}" />
                    </div>

                    <!-- Kategori -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Kategori</span>
                        </label>
                        <select name="kategori_id" class="select select-bordered w-full">
                            <option value="" disabled>Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('kategori_id', $event->kategori_id) == $category->id ? 'selected' : '' }}>
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
                            <span class="label-text-alt">Format: JPG, PNG, max 5MB. Kosongkan jika tidak ingin mengubah gambar.</span>
                        </label>
                    </div>

                    <!-- Preview Gambar -->
                    <div id="imagePreview" class="overflow-hidden {{ $event->gambar ? '' : 'hidden' }}">
                        <label class="label">
                            <span class="label-text font-semibold">Preview Gambar</span>
                        </label>
                        <div class="avatar max-w-sm">
                            <div class="w-full rounded-lg">
                                <img id="previewImg"
                                    src="{{ $event->gambar ? asset('images/events/' . $event->gambar) : '' }}"
                                    alt="Preview">
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
                            value="{{ old('publish_at', $event->publish_at ? \Carbon\Carbon::parse($event->publish_at)->format('Y-m-d\TH:i') : '') }}" />
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
            @if ($event->gambar)
                previewImg.src = "{{ asset('images/events/' . $event->gambar) }}";
                imagePreview.classList.remove('hidden');
            @else
                imagePreview.classList.add('hidden');
            @endif
        });
    </script>
</x-layouts.admin>