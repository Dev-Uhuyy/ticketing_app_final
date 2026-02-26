<x-layouts.app>
<section class="max-w-xl mx-auto py-16 px-6">

    <div class="bg-base-100 rounded-2xl shadow-xl p-8">

        <h1 class="text-3xl font-bold text-center mb-2">
            Beri Review
        </h1>

        <p class="text-center text-gray-500 mb-10">
            {{ $event->judul }}
        </p>

        <form method="POST" action="{{ route('reviews.store', $event) }}" class="space-y-8">
            @csrf

            {{-- Rating --}}
            <div class="text-center">

                <p id="rating-text" class="text-lg font-semibold mb-4 text-gray-600">
                    Pilih rating kamu
                </p>

                <div class="flex flex-row-reverse justify-center gap-2">

                    @for ($i = 5; $i >= 1; $i--)
                        <input
                            type="radio"
                            name="rate"
                            value="{{ $i }}"
                            id="star{{ $i }}"
                            class="hidden peer"
                            required
                        />

                        <label
                            for="star{{ $i }}"
                            class="text-4xl cursor-pointer text-gray-300
                                transition-all duration-200
                                peer-checked:text-yellow-400
                                hover:text-yellow-400
                                hover:scale-125">
                            ★
                        </label>
                    @endfor

                </div>

                @error('rate')
                    <p class="text-error text-sm mt-3">{{ $message }}</p>
                @enderror
            </div>

            {{-- Review --}}
            <div>
                <label class="block font-semibold mb-2">
                    Ulasan (Opsional)
                </label>

                <textarea
                    name="review"
                    maxlength="1000"
                    rows="4"
                    id="review-textarea"
                    class="w-full rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all p-4 resize-none"
                    placeholder="Bagikan pengalaman kamu tentang event ini..."
                >{{ old('review') }}</textarea>

                <div class="flex justify-between mt-2 text-sm text-gray-500">
                    <span>Tulis pengalaman jujur kamu</span>
                    <span id="char-count">0 / 1000</span>
                </div>

                @error('review')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex justify-between items-center pt-4">
                <a href="{{ url()->previous() }}"
                   class="text-gray-500 hover:text-gray-700 transition">
                   Batal
                </a>

                <button type="submit"
                        class="px-8 py-3 rounded-xl btn-primary text-black font-semibold shadow-lg hover:scale-105 hover:shadow-xl transition-all duration-200">
                    Kirim Review
                </button>
            </div>

        </form>
    </div>

</section>


{{-- Script Rating Text + Counter --}}
<script>
    const ratingText = document.getElementById('rating-text');
    const radios = document.querySelectorAll('input[name="rate"]');
    const textarea = document.getElementById('review-textarea');
    const charCount = document.getElementById('char-count');

    const ratingLabels = {
        1: "Sangat Buruk 😞",
        2: "Buruk 😕",
        3: "Biasa 😐",
        4: "Bagus 😊",
        5: "Luar Biasa 🤩"
    };

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            ratingText.textContent = ratingLabels[radio.value];
            ratingText.classList.add("text-yellow-500");
        });
    });

    textarea.addEventListener('input', () => {
        charCount.textContent = textarea.value.length + " / 1000";
    });
</script>

</x-layouts.app>
