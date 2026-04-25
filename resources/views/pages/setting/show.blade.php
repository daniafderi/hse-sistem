<x-layouts.app title="Profile Saya">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Profile Saya</h1>
                <p class="text-sm text-gray-500">Kelola informasi akun dan keamanan</p>
            </div>
            <a href="{{ url()->previous() }}"
                    class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 px-4 py-2 rounded-lg text-sm shadow-sm transition">
                    Kembali
                </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Informasi User -->
            <div class="md:col-span-2 bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="ri-user-line text-indigo-500 text-xl"></i>
                    <h2 class="text-lg font-semibold text-gray-800">Informasi Akun</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <label class="text-gray-500">Nama Lengkap</label>
                        <input type="text" value="{{ Auth::user()->name }}" disabled
                            class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-700">
                    </div>

                    <div>
                        <label class="text-gray-500">Email</label>
                        <input type="email" value="{{ Auth::user()->email }}" disabled
                            class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-700">
                    </div>

                    <div>
                        <label class="text-gray-500">Role</label>
                        <input type="text" value="{{ Auth::user()->role }}" disabled
                            class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-700">
                    </div>

                    <div>
                        <label class="text-gray-500">Bergabung Sejak</label>
                        <input type="text" value="{{ Auth::user()->created_at }}" disabled
                            class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-700">
                    </div>
                </div>
            </div>
            <!-- Ubah Password -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="ri-lock-password-line text-amber-500 text-xl"></i>
                    <h2 class="text-lg font-semibold text-gray-800">Ubah Password</h2>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="text-sm text-gray-500">Password Lama</label>
                        <input name="current_password" type="password" placeholder="••••••••"
                            class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">Password Baru</label>
                        <input name="password" type="password" placeholder="Minimal 8 karakter"
                            class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">Konfirmasi Password Baru</label>
                        <input name="password_confirmation" type="password" placeholder="Ulangi password baru"
                            class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-indigo-500 transition">
                        Simpan Perubahan
                    </button>
                </form>

            </div>

        </div>
    </div>

</x-layouts.app>
