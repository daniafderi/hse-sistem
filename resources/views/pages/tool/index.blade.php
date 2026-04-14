<x-layouts.app title="Equipment">
    <div class="bg-white rounded-lg shadow-md min-h-screen p-4 sm:p-6" x-data="{ open: false, type: 'in' }">
        <div class="max-w-7xl mx-auto space-y-4">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">
                        Daftar Alat & APD
                    </h1>
                    <p class="text-gray-500 text-sm mt-1">
                        Manajemen data alat kerja dan perlengkapan keselamatan
                    </p>
                </div>
                <div class="inline-flex gap-4">
                    <button @click="open = true"
                        class="px-4 text-sm font-medium py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                        + Transaksi Stok
                    </button>
                    <a href="{{ route('tools.create') }}"
                        class="inline-flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                        <i class="ri-add-line"></i> Tambah Alat / APD
                    </a>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="relative w-full md:max-w-md">
                    <i class="ri-search-line absolute left-3 top-2.5 text-gray-400"></i>
                    <input type="text" placeholder="Cari alat atau APD..."
                        class="pl-10 w-full border border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="flex flex-col sm:flex-row gap-2">
                    <select
                        class="border border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2">
                        <option>Semua Kategori</option>
                        <option>APD</option>
                        <option>Alat Berat</option>
                        <option>Perkakas</option>
                    </select>
                    <select
                        class="border border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2">
                        <option>Semua Status</option>
                        <option>Aktif</option>
                        <option>Rusak</option>
                        <option>Dipinjam</option>
                    </select>
                </div>
            </div>

            <!-- Table Wrapper (Responsive Scroll) -->
            <div class="bg-white rounded-lg shadow-sm border overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-600 font-medium">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left">Nama Alat / APD</th>
                            <th class="px-4 sm:px-6 py-3 text-center">Stok</th>
                            <th class="px-4 sm:px-6 py-3 text-center hidden md:table-cell">
                                Status
                            </th>
                            <th class="px-4 sm:px-6 py-3 text-center hidden lg:table-cell">
                                Terakhir Diperbarui
                            </th>
                            <th class="px-4 sm:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($tools as $tool)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 sm:px-6 py-4 font-medium text-gray-800">
                                    {{ $tool['name'] }}
                                </td>

                                <td class="px-4 sm:px-6 py-4 text-center">
                                    <span @class([
                                        'px-2 py-1 rounded-full text-xs font-semibold',
                                        'bg-green-100 text-green-700' => $tool['stock'] > 5,
                                        'bg-yellow-100 text-yellow-700' =>
                                            $tool['stock'] > 0 && $tool['stock'] <= 5,
                                        'bg-rose-100 text-rose-700' => $tool['stock'] == 0,
                                    ])>
                                        {{ $tool['stock'] }}
                                    </span>
                                </td>

                                <td class="px-4 sm:px-6 py-4 text-center hidden md:table-cell">
                                    @if ($tool['stock'] > 50)
                                        <span class="badge bg-green-100 text-green-700">Aktif</span>
                                    @elseif ($tool['stock'] >= 10)
                                        <span class="badge bg-yellow-100 text-yellow-700">Menipis</span>
                                    @else
                                        <span class="badge bg-rose-100 text-rose-700">Hampir Habis</span>
                                    @endif
                                </td>

                                <td class="px-4 sm:px-6 py-4 text-center text-gray-500 hidden lg:table-cell">
                                    {{ $tool['updated_at'] }}
                                </td>

                                <td class="px-4 sm:px-6 py-4 text-center">
                                    <a href="{{ route('tools.show', $tool['id']) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium
                                       text-indigo-600 hover:text-white hover:bg-indigo-600
                                       rounded-lg border border-indigo-600 transition">
                                        <i class="ri-eye-line"></i>
                                        <span class="hidden sm:inline">Detail</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                                    Belum ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
<!-- Overlay -->
        <div x-show="open" x-transition
            class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 mt-0">

            <!-- Modal -->
            <div @click.outside="open = false" x-show="open" x-transition.scale
                class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">
                        Transaksi Stok APD
                    </h2>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                        ✕
                    </button>
                </div>

                <!-- Form -->
                <form class="space-y-4" method="POST" action="{{ route('stock-transactions.store') }}">
                    @csrf
                    <!-- Select APD -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih APD
                        </label>
                        <select name="tool_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            @foreach ($tools as $tool)
                                <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Transaksi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Transaksi
                        </label>

                        <div class="grid grid-cols-2 gap-3">

                            <!-- Stok Masuk -->
                            <label @click="type = 'in'"
                                :class="type === 'in' ? 'border-green-500 bg-green-50' : 'border-gray-200'"
                                class="cursor-pointer border rounded-xl p-3 flex items-center gap-2 transition">
                                <input type="radio" value="in" x-model="type" class="hidden">
                                <span class="text-green-600 text-lg">⬆</span>
                                <div>
                                    <p class="font-medium text-gray-800">Stok Masuk</p>
                                    <p class="text-xs text-gray-500">Penambahan stok</p>
                                </div>
                            </label>

                            <!-- Stok Keluar -->
                            <label @click="type = 'out'"
                                :class="type === 'out' ? 'border-red-500 bg-red-50' : 'border-gray-200'"
                                class="cursor-pointer border rounded-xl p-3 flex items-center gap-2 transition">
                                <input type="radio" value="out" x-model="type" class="hidden">
                                <span class="text-red-600 text-lg">⬇</span>
                                <div>
                                    <p class="font-medium text-gray-800">Stok Keluar</p>
                                    <p class="text-xs text-gray-500">Pengurangan stok</p>
                                </div>
                            </label>

                        </div>
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah
                        </label>
                        <input type="number" min="1"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan jumlah">
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Keterangan (opsional)
                        </label>
                        <textarea class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" rows="2"
                            placeholder="Contoh: pembelian baru / pemakaian proyek"></textarea>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" @click="open = false"
                            class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                            Batal
                        </button>

                        <button type="submit"
                            :class="type === 'in'
                                ?
                                'bg-green-600 hover:bg-green-700' :
                                'bg-red-600 hover:bg-red-700'"
                            class="px-4 py-2 text-white rounded-lg shadow transition">
                            Simpan
                        </button>
                    </div>

                </form>
            </div>
        </div>
        </div>
    </div>
    <!-- Alpine Init -->
    <div x-data="{ open: false, type: 'in' }" class="p-6">

        <!-- Button Open Modal -->
        <button @click="open = true"
            class="px-5 py-2.5 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
            + Transaksi Stok
        </button>

        
    </div>
</x-layouts.app>
