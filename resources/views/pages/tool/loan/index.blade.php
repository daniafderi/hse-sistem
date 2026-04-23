<x-layouts.app title="Index Peminjaman">
    <div class="bg-white rounded-lg shadow-md min-h-screen p-4 sm:p-6">
        <div class="max-w-7xl mx-auto space-y-5">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">
                        Daftar Peminjaman Alat
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Monitoring peminjaman dan pengembalian alat kerja
                    </p>
                </div>
                @can('isHseKantor')
                <a href="{{ route('loans.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2
                          bg-indigo-600 hover:bg-indigo-700 text-white text-sm
                          font-medium rounded-lg shadow transition">
                    <i class="ri-add-line"></i>
                    Tambah Peminjaman
                </a>
                    
                @endcan
            </div>

            <!-- Search -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="relative w-full sm:max-w-sm">
                    <i class="ri-search-line absolute left-3 top-2.5 text-gray-400"></i>
                    <input type="text"
                        placeholder="Cari nama peminjam..."
                        class="pl-10 w-full border border-gray-200 rounded-lg text-sm
                               focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-600 font-medium">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left">No</th>
                            <th class="px-4 sm:px-6 py-3 text-left">Nama Peminjam</th>
                            <th class="px-4 sm:px-6 py-3 text-left hidden md:table-cell">
                                Tanggal Pinjam
                            </th>
                            <th class="px-4 sm:px-6 py-3 text-left hidden lg:table-cell">
                                Tanggal Kembali
                            </th>
                            <th class="px-4 sm:px-6 py-3 text-center">Status</th>
                            <th class="px-4 sm:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($loans as $loan)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 sm:px-6 py-4 text-gray-500">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-4 sm:px-6 py-4 font-medium text-gray-800">
                                    {{ $loan->peminjam }}
                                </td>

                                <td class="px-4 sm:px-6 py-4 hidden md:table-cell">
                                    {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}
                                </td>

                                <td class="px-4 sm:px-6 py-4 hidden lg:table-cell">
                                    @if ($loan->returned_at)
                                        {{ \Carbon\Carbon::parse($loan->returned_at)->format('d M Y') }}
                                    @else
                                        <span class="text-gray-400 italic">Belum ditentukan</span>
                                    @endif
                                </td>

                                <td class="px-4 sm:px-6 py-4 text-center">
                                    @php
                                        $statusColors = [
                                            'borrowed' => 'bg-yellow-100 text-yellow-700',
                                            'partial_return' => 'bg-blue-100 text-blue-700',
                                            'returned' => 'bg-green-100 text-green-700',
                                        ];
                                    @endphp

                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $statusColors[$loan->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                                    </span>
                                </td>

                                <td class="px-4 sm:px-6 py-4 text-center">
                                    <a href="{{ route('loans.show', $loan->id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5
                                              text-sm font-medium text-indigo-600
                                              hover:text-white hover:bg-indigo-600
                                              rounded-lg border border-indigo-600 transition">
                                        <i class="ri-eye-line"></i>
                                        <span class="hidden sm:inline">Detail</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                                    Belum ada data peminjaman
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4">
                <p class="text-sm text-gray-500">
                    Menampilkan {{ $loans->count() }} data
                </p>
                <div>
                    {{ $loans->links() }}
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>
