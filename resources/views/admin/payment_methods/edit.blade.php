<x-layouts.admin title="Edit Metode Pembayaran">
    
    <div class="container mx-auto p-10">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold mb-4">Edit Metode Pembayaran</h1>
            <a href="{{ route('superadmin.payment-methods.index') }}" class="btn btn-ghost">← Kembali</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-xs max-w-2xl">
            <form action="{{ route('superadmin.payment-methods.update', $paymentMethod->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text">Metode Pembayaran <span class="text-red-500">*</span></span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name', $paymentMethod->name) }}"
                        placeholder="Contoh: Bank Transfer, Kartu Kredit" 
                        class="input input-bordered w-full @error('name') input-error @enderror"
                        required
                    />
                    @error('name')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('superadmin.payment-methods.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
