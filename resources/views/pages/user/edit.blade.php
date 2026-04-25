<x-layouts.app title="Edit User">

    <div class="flex justify-between items-center">
        <x-title-page>Edit User</x-title-page>

        <a href="{{ url()->previous() }}"
            class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 px-4 py-2 rounded-lg text-sm shadow-sm transition w-full sm:w-auto justify-center">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>

    </div>
    <div class="space-y-6">

        <!-- ALERT -->
        @if (session('success'))
            <div class="p-4 bg-green-100 text-green-700 rounded-xl shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- ========================= -->
        <!-- BOX 1: EDIT DATA USER -->
        <!-- ========================= -->
        <div class="bg-white rounded-2xl shadow p-6">


            <form action="{{ route('user.update', $user->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div class="mt-0">
                    <label class="block text-sm font-medium mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none border-gray-300 border-solid">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none border-gray-300 border-solid">
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium mb-1">Role</label>
                    <select name="role"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 border-gray-300 border-solid">
                        <option value="Supervisor" {{ $user->role == 'Supervisor' ? 'selected' : '' }}>Supervisor
                        </option>
                        <option value="HSE Admin" {{ $user->role == 'HSE Admin' ? 'selected' : '' }}>HSE Admin</option>
                        <option value="HSE Kantor" {{ $user->role == 'HSE Kantor' ? 'selected' : '' }}>HSE Kantor
                        </option>
                        <option value="HSE Lapangan" {{ $user->role == 'HSE Lapangan' ? 'selected' : '' }}>HSE Lapangan
                        </option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- ========================= -->
        <!-- BOX 2: RESET PASSWORD -->
        <!-- ========================= -->
        <div x-data="{ openModal: false }" class="bg-white rounded-2xl shadow p-6 border-l-4 border-red-500">

            <h2 class="text-xl font-semibold mb-2 text-red-600">
                Reset Password
            </h2>

            <p class="text-gray-600 mb-4">
                Jika Anda mereset password, sistem akan:
            </p>

            <ul class="list-disc pl-5 text-gray-600 space-y-1 mb-6">
                <li>Menghasilkan password baru secara otomatis</li>
                <li>Password lama user tidak akan bisa digunakan lagi</li>
                <li>User harus menggunakan password baru untuk login</li>
            </ul>

            <button @click="openModal = true"
                class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg shadow">
                Reset Password
            </button>

            <!-- MODAL -->
            <div x-show="openModal" x-transition
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

                <div class="bg-white rounded-2xl p-6 w-96 shadow-lg">
                    <h3 class="text-lg font-semibold mb-3">Konfirmasi Reset</h3>

                    <p class="text-gray-600 mb-6">
                        Apakah Anda yakin ingin mereset password user ini?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <div class="flex justify-end gap-3">
                        <button @click="openModal = false" class="px-4 py-2 bg-gray-300 rounded-lg">
                            Batal
                        </button>

                        <form action="{{ route('user.resetPassword', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg">
                                Ya, Reset
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================= -->
        <!-- BOX 3: DELETE USER -->
        <!-- ========================= -->
        <div x-data="{ openDelete: false }" class="bg-white rounded-2xl shadow p-6 border-l-4 border-red-700">

            <h2 class="text-xl font-semibold mb-2 text-red-700">
                Hapus User
            </h2>

            <p class="text-gray-600 mb-4">
                Menghapus user akan:
            </p>

            <ul class="list-disc pl-5 text-gray-600 space-y-1 mb-6">
                <li>Menghapus seluruh data user secara permanen</li>
                <li>User tidak akan bisa login kembali</li>
                <li>Tindakan ini <span class="font-semibold text-red-600">tidak dapat dibatalkan</span></li>
            </ul>

            <button @click="openDelete = true"
                class="bg-red-700 hover:bg-red-800 text-white px-5 py-2 rounded-xl shadow">
                Hapus User
            </button>

            <!-- MODAL DELETE -->
            <div x-show="openDelete" x-transition
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

                <div class="bg-white rounded-2xl p-6 w-96 shadow-lg">
                    <h3 class="text-lg font-semibold mb-3 text-red-700">
                        Konfirmasi Hapus
                    </h3>

                    <p class="text-gray-600 mb-4">
                        Apakah Anda yakin ingin menghapus user ini?
                    </p>

                    <p class="text-sm text-red-600 mb-6">
                        Semua data akan hilang dan tidak bisa dikembalikan.
                    </p>

                    <div class="flex justify-end gap-3">
                        <button @click="openDelete = false" class="px-4 py-2 bg-gray-300 rounded-lg">
                            Batal
                        </button>

                        <form action="{{ route('user.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="px-4 py-2 bg-red-700 text-white rounded-lg">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
