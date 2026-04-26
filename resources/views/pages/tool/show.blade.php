<x-layouts.app title="Detail APD">
    <div class="space-y-6" x-data="{openModal:false}">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Detail APD</h1>
                <p class="text-sm text-gray-500">Monitoring stok & peminjaman APD</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <button class="hidden px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg shadow hover:bg-indigo-500">
                    <i class="ri-line-chart-line mr-1"></i> Grafik Stok
                </button>

                <button class="hidden px-4 py-2 bg-amber-500 text-white text-sm rounded-lg shadow hover:bg-amber-400">
                    <i class="ri-hand-coin-line mr-1"></i> Pinjam APD
                </button>

                <button class="hidden px-4 py-2 bg-gray-700 text-white text-sm rounded-lg shadow hover:bg-gray-600">
                    <i class="ri-printer-line mr-1"></i> Print
                </button>

                <button
                    class="hidden px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg shadow hover:bg-emerald-500">
                    <i class="ri-download-line mr-1"></i> Download
                </button>

                @can('isSupervisor')
                @if ($tool->validation === 'menunggu')
                <button @click="openModal = true"
                    class="w-full sm:w-auto px-4 py-2 bg-blue-700 hover:bg-blue-600 text-white rounded-lg shadow text-sm">
                    <i class="ri-check-line"></i> Validasi
                </button>
                    
                @endif
                @endcan

                <a href="{{ route('tools.edit', $tool) }}"
                    class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 px-4 py-2 rounded-lg text-sm shadow-sm transition w-full sm:w-auto justify-center">
                    <i class="ri-edit-line"></i> Edit
                </a>

                <a href="{{ route('tools.index') }}"
                    class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 px-4 py-2 rounded-lg text-sm shadow-sm transition w-full sm:w-auto justify-center">
                    <i class="ri-arrow-left-line"></i> Kembali
                </a>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <i class="ri-shield-line text-indigo-500 text-xl"></i>
                        <h2 class="text-lg font-semibold text-gray-800">Informasi APD</h2>
                    </div>

                    {{-- STOCK WARNING --}}
                    @if ($tool['stock'] > $tool['stock_minimum'] * 0.1)
                        <span
                            class="text-xs font-semibold px-2 py-1 rounded-full badge bg-green-100 text-green-700">Stock
                            Aman</span>
                    @elseif ($tool['stock'] <= $tool['stock_minimum'] * 0.1)
                        <span
                            class="text-xs font-semibold px-2 py-1 rounded-full badge bg-yellow-100 text-yellow-700">Menipis</span>
                    @else
                        <span
                            class="text-xs font-semibold px-2 py-1 rounded-full badge bg-rose-100 text-rose-700">Hampir
                            Habis</span>
                    @endif
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama APD</span>
                        <span class="font-medium text-gray-800">{{ $tool->name }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Kategori</span>
                        <span class="font-medium text-gray-800">APD</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Stok Saat Ini</span>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            {{ $tool->stock }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Stok Minimum</span>
                        <span class="text-gray-800">{{ $tool['stock_minimum'] * 0.1 }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Satuan</span>
                        <span class="text-gray-800">Unit</span>
                    </div>

                    <div class="hidden">
                        <span class="text-gray-500">Deskripsi</span>
                        <p class="text-gray-700 mt-1">
                            Helm pelindung standar kerja lapangan.
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="ri-exchange-line text-emerald-500 text-xl"></i>
                    <h2 class="text-lg font-semibold text-gray-800">History Stok</h2>
                </div>

                <div class="space-y-3 max-h-[360px] overflow-y-auto">
                    @foreach ($tool->stockTransaction as $historyStock)
                        <div class="flex items-start gap-3 border rounded-lg p-3">
                            @if ($historyStock['type'] === 'in')
                                <i class="ri-arrow-up-line text-green-600"></i>
                            @else
                                <i class="ri-arrow-down-line text-red-600"></i>
                            @endif
                            <div class="flex-1 text-sm">
                                <p class="font-medium text-gray-800">{{ $historyStock['note'] }}</p>
                                <p class="text-xs text-gray-500">{{ $historyStock['created_at'] }} • Sistem</p>
                            </div>
                            <div
                                class="@if ($historyStock['type'] === 'in') text-green-600
                        @else
                            text-red-600 @endif font-semibold">
                                {{ $historyStock['type'] === 'in' ? '+' . $historyStock['quantity'] : '-' . $historyStock['quantity'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6 lg:col-span-3">
            <div class="flex items-center gap-2 mb-4">
                <i class="ri-hand-coin-line text-amber-500 text-xl"></i>
                <h2 class="text-lg font-semibold text-gray-800">History Peminjaman</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600">
                            <th class="px-4 py-2 text-left">Peminjam</th>
                            <th class="px-4 py-2 text-center">Jumlah</th>
                            <th class="px-4 py-2 text-left">Tanggal Pinjam</th>
                            <th class="px-4 py-2 text-left">Tanggal Kembali</th>
                            <th class="px-4 py-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">

                        @if ($tool->loanItems->count() > 0)
                            @foreach ($tool->loanItems as $item)
                                <tr>
                                    <td class="px-4 py-2">{{ $item->loan->peminjam }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td>{{ $item->loan->borrowed_at }}</td>
                                    <td>{{ $item->loan->returned_at }}</td>
                                    <td class="text-center">
                                        <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                            {{ $item->loan->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-4 py-3 text-center" colspan="6">Belum ada data peminjaman</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Box -->
            <div x-data="{ confirmDelete: false }" class="p-6 mt-6 bg-white rounded-lg shadow-sm border border-gray-100">

                <div class="flex items-start sm:items-center justify-between gap-4 flex-wrap">
                    <div class="min-w-[200px]">
                        <h2 class="text-lg font-semibold text-gray-800">Hapus APD</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Menghapus APD juga akan menghapus semua peminjaman terkait.
                        </p>
                    </div>

                    <div class="p-2 bg-red-50 rounded-lg self-start sm:self-center">
                        <i class="ri-delete-bin-6-line text-red-500 text-xl"></i>
                    </div>
                </div>

                <button @click="confirmDelete = true"
                    class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-700 text-white rounded-lg hover:bg-red-600 transition font-medium">
                    <i class="ri-delete-bin-line text-lg"></i> Hapus
                </button>

                <!-- Modal Konfirmasi -->
                <div x-show="confirmDelete" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50"
                    x-transition.opacity>

                    <div class="bg-white w-full max-w-sm rounded-lg p-6 shadow-xl" x-transition.scale>

                        <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Penghapusan</h3>
                        <p class="text-sm text-gray-500 mt-2">
                            Apakah Anda yakin? Tindakan ini tidak dapat dibatalkan.
                        </p>

                        <div class="flex items-center justify-end gap-3 mt-6">

                            <button @click="confirmDelete = false"
                                class="px-4 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-100 transition">
                                Batal
                            </button>

                            <form method="POST" action="{{ route('tools.destroy', $tool->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 rounded-lg bg-red-500 text-sm text-white hover:bg-red-600 transition">
                                    Ya, Hapus
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        <div x-show="openModal" x-transition class="fixed inset-0 z-40 bg-black/40 flex items-center justify-center">
    <div class="bg-white w-full max-w-md mx-4 p-6 rounded-xl">
        <h3 class="font-semibold mb-4">Validasi APD</h3>

        <form method="POST" action="{{ route('tools.validate',$tool->id) }}">
            @csrf
            <select name="status" class="w-full border rounded-lg mb-3 px-3 py-2">
                <option value="">Pilih Status</option>
                <option value="valid">Valid</option>
                <option value="ditolak">Ditolak</option>
            </select>

            <textarea name="komentar" rows="3"
                class="w-full border rounded-lg mb-4 px-3 py-2"
                placeholder="Catatan"></textarea>

            <div class="flex justify-end gap-2">
                <button type="button" @click="openModal=false"
                    class="px-4 py-2 border rounded-lg">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
    </div>

</x-layouts.app>
