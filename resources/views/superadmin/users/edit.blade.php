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
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Contoh: Ahmad Fauzi" class="input input-bordered w-full @error('name') input-error @enderror" required />
                        @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Email</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="user@example.com" class="input input-bordered w-full @error('email') input-error @enderror" required />
                        @error('email') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Role</span>
                        </label>
                        <select name="role" class="select select-bordered w-full @error('role') select-error @enderror" required>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">No HP (Opsional)</span>
                        </label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="Contoh: 081234567890" class="input input-bordered w-full @error('no_hp') input-error @enderror" />
                        @error('no_hp') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Password Baru</span>
                            </label>
                            <input type="password" name="password" placeholder="••••••••" class="input input-bordered w-full @error('password') input-error @enderror" />
                            @error('password') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Konfirmasi Password Baru</span>
                            </label>
                            <input type="password" name="password_confirmation" placeholder="••••••••" class="input input-bordered w-full" />
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
