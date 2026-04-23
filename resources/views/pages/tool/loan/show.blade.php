<x-layouts.app title="Detail Peminjaman">
    <div class="p-4 sm:p-6 space-y-6" x-data="{ openReturnModal: false }">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">
                    Peminjaman #{{ $loan->id }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Oleh:
                    <span class="font-medium text-gray-700">{{ $loan->peminjam }}</span> •
                    {{ $loan->created_at->format('d M Y, H:i') }}
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2">
                @can('isHseKantor')
                <button @click="openReturnModal = true"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2
                           text-white bg-indigo-600 rounded-lg shadow
                           hover:bg-indigo-700 transition text-sm">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    Proses Pengembalian
                </button>
                    
                @endcan

                <a href="{{ route('loans.index') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2
                           text-gray-600 bg-gray-100 rounded-lg
                           hover:bg-gray-200 transition text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Detail Peminjaman -->
        <div class="p-5 bg-white border rounded-xl shadow-sm">
            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-4">
                Detail Item
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-50 border-b text-gray-700">
                        <tr>
                            <th class="px-4 py-3 font-medium">Alat</th>
                            <th class="px-4 py-3 font-medium text-center">Jumlah</th>
                            <th class="px-4 py-3 font-medium text-center hidden md:table-cell">Sudah Kembali</th>
                            <th class="px-4 py-3 font-medium text-center hidden sm:table-cell">Sisa</th>
                            <th class="px-4 py-3 font-medium text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->items as $item)
                            @php $sisa = $item->quantity - $item->returned_quantity; @endphp
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $item->tool->name }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-4 py-3 text-center hidden md:table-cell">
                                    {{ $item->returned_quantity }}
                                </td>
                                <td class="px-4 py-3 text-center hidden sm:table-cell">
                                    {{ $sisa }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($sisa == 0)
                                        <span class="px-2 py-1 text-xs font-semibold
                                            bg-green-100 text-green-700 rounded-full">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold
                                            bg-yellow-100 text-yellow-700 rounded-full">
                                            Belum Selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Riwayat Pengembalian -->
        <div class="p-5 bg-white border rounded-xl shadow-sm">
            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-4">
                Riwayat Pengembalian
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-600">
                    <thead class="bg-gray-50 border-b text-gray-700">
                        <tr>
                            <th class="px-4 py-3 font-medium text-left">Tanggal</th>
                            <th class="px-4 py-3 font-medium text-left">Alat</th>
                            <th class="px-4 py-3 font-medium text-center">Jumlah</th>
                            <th class="px-4 py-3 font-medium hidden sm:table-cell text-left">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returnRecords as $log)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($log->returned_at)->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $log->name }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $log->quantity }}
                                </td>
                                <td class="px-4 py-3 hidden sm:table-cell">
                                    {{ $log->condition ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-400">
                                    Belum ada pengembalian
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Pengembalian -->
        <div x-show="openReturnModal" x-cloak
            class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">

            <div @click.away="openReturnModal = false"
                class="bg-white w-full max-w-3xl rounded-2xl shadow-lg
                       p-5 sm:p-6 space-y-5 max-h-[90vh] overflow-y-auto">

                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">
                        Pengembalian Barang
                    </h3>
                    <button @click="openReturnModal = false"
                        class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('loans.return', $loan) }}" method="POST" class="space-y-6">
                    @csrf

                    <p class="text-sm text-gray-500">
                        Isi jumlah dan kondisi alat yang dikembalikan.
                    </p>

                    <div class="divide-y divide-gray-100 border rounded-xl">
                        @foreach ($loan->items as $item)
                            @php $sisa = $item->quantity - $item->returned_quantity; @endphp
                            <div class="p-4 flex flex-col gap-4 sm:flex-row sm:items-center">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-800 text-sm">
                                        {{ $item->tool->name }}
                                    </h4>
                                    <input type="hidden" name="items[{{ $loop->index }}][item_id]"
                                           value="{{ $item->id }}">
                                    <p class="text-xs text-gray-500 mt-1">
                                        Total: {{ $item->quantity }} |
                                        Dikembalikan: {{ $item->returned_quantity }} |
                                        Sisa: {{ $sisa }}
                                    </p>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                            Jumlah
                                        </label>
                                        <input type="number"
                                            name="items[{{ $loop->index }}][quantity]"
                                            min="0" max="{{ $sisa }}"
                                            class="w-24 border-gray-300 rounded-lg text-sm">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                            Kondisi
                                        </label>
                                        <input type="text"
                                            name="items[{{ $loop->index }}][condition_on_return]"
                                            class="w-full border-gray-300 rounded-lg text-sm"
                                            placeholder="Baik / Lecet / Rusak">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t">
                        <button type="button" @click="openReturnModal = false"
                            class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                lucide.createIcons();
            });
        </script>

    </div>
</x-layouts.app>
