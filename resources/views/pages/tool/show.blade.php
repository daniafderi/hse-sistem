<x-layouts.app title="Detail APD">
    <div class="space-y-6">
    
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
    
                <button class="px-4 py-2 bg-amber-500 text-white text-sm rounded-lg shadow hover:bg-amber-400">
                    <i class="ri-hand-coin-line mr-1"></i> Pinjam APD
                </button>
    
                <button class="hidden px-4 py-2 bg-gray-700 text-white text-sm rounded-lg shadow hover:bg-gray-600">
                    <i class="ri-printer-line mr-1"></i> Print
                </button>
    
                <button class="hidden px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg shadow hover:bg-emerald-500">
                    <i class="ri-download-line mr-1"></i> Download
                </button>
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
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                        Stok Minimum
                    </span>
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
                        <span class="text-gray-800">5</span>
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
                    {{-- STOCK MASUK --}}
                    <div class="flex items-start gap-3 border rounded-lg p-3">
                        <i class="ri-arrow-up-line text-green-600"></i>
                        <div class="flex-1 text-sm">
                            <p class="font-medium text-gray-800">Penambahan stok APD</p>
                            <p class="text-xs text-gray-500">01 Jan 2025 • Admin</p>
                        </div>
                        <div class="text-green-600 font-semibold">+10</div>
                    </div>
    
                    {{-- STOCK KELUAR --}}
                    <div class="flex items-start gap-3 border rounded-lg p-3">
                        <i class="ri-arrow-down-line text-red-600"></i>
                        <div class="flex-1 text-sm">
                            <p class="font-medium text-gray-800">Pengurangan karena peminjaman</p>
                            <p class="text-xs text-gray-500">03 Jan 2025 • Sistem</p>
                        </div>
                        <div class="text-red-600 font-semibold">-7</div>
                    </div>
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
                        <tr>
                            <td class="px-4 py-2">Budi Santoso</td>
                            <td class="text-center">2</td>
                            <td>02 Jan 2025</td>
                            <td>-</td>
                            <td class="text-center">
                                <span class="px-2 py-1 rounded-full text-xs bg-amber-100 text-amber-700">
                                    Dipinjam
                                </span>
                            </td>
                        </tr>

                        
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
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>
