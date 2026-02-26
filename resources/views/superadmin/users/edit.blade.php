<x-layouts.admin title="Edit User">
    <div class="container mx-auto p-10">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Edit User: {{ $user->name }}</h2>

                <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Nama Lengkap</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            placeholder="Contoh: Ahmad Fauzi"
                            class="input input-bordered w-full @error('name') input-error @enderror" required />
                        @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Email</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            placeholder="user@example.com"
                            class="input input-bordered w-full @error('email') input-error @enderror" required />
                        @error('email') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Role</span>
                        </label>
                        <select name="role" class="select select-bordered w-full @error('role') select-error @enderror"
                            required>
                            @foreach($roles as $value => $label)
                            <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">No HP (Opsional)</span>
                        </label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                            placeholder="Contoh: 081234567890" inputmode="numeric" pattern="[0-9]*"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="input input-bordered w-full @error('no_hp') input-error @enderror" />
                        @error('no_hp') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Password (Kosongkan jika tidak diganti)</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password"
                                    class="password-input input input-bordered w-full pr-12 @error('password') input-error @enderror"
                                    placeholder="••••••••" />

                                <button type="button"
                                    class="toggle-password absolute inset-y-0 right-3 flex items-center text-gray-500">
                                    <svg class="eye-open hidden" xmlns="http://www.w3.org/2000/svg" width="20"
                                        height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0
                                        1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-.722-3.25" />
                                        <path d="M2 8a10.645 10.645 0 0 0 20 0" />
                                        <path d="m20 15-1.726-2.05" />
                                        <path d="m4 15 1.726-2.05" />
                                        <path d="m9 18 .722-3.25" />
                                    </svg>
                                </button>
                            </div>
                            @error('password') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Konfirmasi Password</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation"
                                    class="password-input input input-bordered w-full pr-12" placeholder="••••••••" />
                                <button type="button"
                                    class="toggle-password absolute inset-y-0 right-3 flex items-center text-gray-500">
                                    <svg class="eye-open hidden" xmlns="http://www.w3.org/2000/svg" width="20"
                                        height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0
                                        1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-.722-3.25" />
                                        <path d="M2 8a10.645 10.645 0 0 0 20 0" />
                                        <path d="m20 15-1.726-2.05" />
                                        <path d="m4 15 1.726-2.05" />
                                        <path d="m9 18 .722-3.25" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-6">
                        <a href="{{ route('superadmin.users.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary">Perbarui User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {

            const wrapper = button.closest('.relative');
            const input = wrapper.querySelector('.password-input');
            const eyeOpen = button.querySelector('.eye-open');
            const eyeClosed = button.querySelector('.eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        });
    });
});
</script>