<x-layouts.admin title="Jawab Review">
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
            <div class="flex items-center justify-between mb-6">
                <h2 class="card-title text-2xl">Jawab Review</h2>
                <a href="{{ route('pengelola.reviews.index') }}" class="btn btn-ghost btn-sm"> Kembali</a>
            </div>
            
            <div class="border border-base-300 rounded-lg p-4 mb-6">
                <div>
                <h3 class="font-bold">Review dari: {{ $review->user->name }}</h3>
                <p class="text-sm mt-2">{{ $review->review }}</p>
                <p class="text-xs mt-2">Rating: {{ $review->rate }}/5 ⭐</p>
                </div>
            </div>

            <form id="answerForm" class="space-y-4" method="post"
                action="{{ route('pengelola.reviews.answer', $review->id) }}">
                @csrf

                <!-- Jawaban Review -->
                <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Jawaban Anda</span>
                </label>
                <textarea name="answer" placeholder="Tulis jawaban untuk review ini..." class="textarea textarea-bordered h-32 w-full"
                    required></textarea>
                </div>

                <!-- Tombol Submit -->
                <div class="card-actions justify-end mt-6">
                <button type="submit" class="btn btn-primary">Kirim Jawaban</button>
                </div>
            </form>
            </div>
        </div>

        <!-- Alert Success -->
        <div id="successAlert" class="alert alert-success mt-4 hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Jawaban berhasil dikirim!</span>
        </div>
    </div>
</x-layouts.admin>
