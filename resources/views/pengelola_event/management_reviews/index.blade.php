<x-layouts.admin title="Manajemen Review">
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
            <h1 class="text-3xl font-semibold mb-4">Manajemen Review</h1>
        </div>
        <div class="overflow-x-auto rounded-box bg-white p-5 shadow-xs">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Event</th>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Jawaban</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $index => $review)
                    <tr>
                        <th>{{ $index + 1 }}</th>
                        <td>{{ $review->event->judul }}</td>
                        <td>{{ $review->user->name }}</td>
                        <td>
                            <div class="badge badge-lg">{{ $review->rate }}/5</div>
                        </td>
                        <td>{{ Str::limit($review->review, 50) }}</td>
                        <td>{{ $review->answer ? Str::limit($review->answer, 50) : '-' }}</td>
                        <td>
                            <a href="{{ route('pengelola.reviews.showAnswer', $review->id) }}" class="btn btn-sm btn-info mr-2">Jawab</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada review tersedia.</td>
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

            <input type="hidden" name="review_id" id="delete_review_id">

            <h3 class="text-lg font-bold mb-4">Hapus Review</h3>
            <p>Apakah Anda yakin ingin menghapus review ini?</p>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Hapus</button>
                <button class="btn" onclick="delete_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <script>
        function openDeleteModal(button) {
            const id = button.dataset.id;
            const form = document.querySelector('#delete_modal form');
            document.getElementById("delete_review_id").value = id;

            form.action = `/pengelola/reviews/${id}`;

            delete_modal.showModal();
        }
    </script>

</x-layouts.admin>