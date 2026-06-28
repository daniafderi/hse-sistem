<x-layouts.app title="Detail Peminjaman">
    <div class="p-3 sm:p-6 space-y-4 sm:space-y-6" x-data="{ openReturnModal: false, preview: false, imageSrc: '' }">

        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">
                    Peminjaman #{{ $loan->id }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Oleh:
                    <span class="font-medium text-gray-700">
                        {{ $loan->peminjam }}
                    </span>
                    •
                    {{ $loan->created_at->format('d M Y, H:i') }}
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">

                @can('isHseKantor')
                    <button @click="openReturnModal = true"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2
                               text-white bg-indigo-600 rounded-lg shadow
                               hover:bg-indigo-700 transition text-sm @if ($loan->status === 'returned')
                                   hidden
                               @endif">

                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        Proses Pengembalian
                    </button>
                @endcan

                <a href="{{ route('loans.index') }}"
                    class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 px-4 py-2 rounded-lg text-sm shadow-sm transition w-full sm:w-auto justify-center">

                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Detail Peminjaman -->
        <div class="p-3 sm:p-5 bg-white border rounded-xl shadow-sm overflow-hidden">

            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-4">
                Detail Item
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-[700px] w-full text-xs sm:text-sm text-left text-gray-600">

                    <thead class="bg-gray-50 border-b text-gray-700">
                        <tr>
                            <th class="px-2 sm:px-4 py-3 font-medium w-14">
                                Gambar
                            </th>

                            <th class="px-2 sm:px-4 py-3 font-medium">
                                Alat
                            </th>

                            <th class="px-2 sm:px-4 py-3 font-medium text-center">
                                Jumlah
                            </th>

                            <th class="px-2 sm:px-4 py-3 font-medium text-center hidden md:table-cell">
                                Sudah Kembali
                            </th>

                            <th class="px-2 sm:px-4 py-3 font-medium text-center hidden sm:table-cell">
                                Sisa
                            </th>

                            <th class="px-2 sm:px-4 py-3 font-medium text-center">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($loan->items as $item)
                            @php
                                $sisa = $item->quantity - $item->returned_quantity;
                            @endphp

                            <tr class="border-b hover:bg-gray-50 transition">

                                <!-- Gambar -->
                                <td class="px-2 sm:px-4 py-3">

                                    <div class="w-14 h-14 rounded-lg overflow-hidden border bg-gray-100 shrink-0">

                                        @if ($item->tool->image_path)
                                            <img src="{{ Storage::url($item->tool->image_path) }}"
                                                alt="{{ $item->tool->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <i data-lucide="image" class="w-5 h-5"></i>
                                            </div>
                                        @endif

                                    </div>
                                </td>

                                <!-- Nama alat -->
                                <td class="px-2 sm:px-4 py-3 font-medium text-gray-800">
                                    {{ $item->tool->name }}
                                </td>

                                <!-- Jumlah -->
                                <td class="px-2 sm:px-4 py-3 text-center">
                                    {{ $item->quantity }}
                                </td>

                                <!-- Returned -->
                                <td class="px-2 sm:px-4 py-3 text-center hidden md:table-cell">
                                    {{ $item->returned_quantity }}
                                </td>

                                <!-- Sisa -->
                                <td class="px-2 sm:px-4 py-3 text-center hidden sm:table-cell">
                                    {{ $sisa }}
                                </td>

                                <!-- Status -->
                                <td class="px-2 sm:px-4 py-3 text-center">

                                    @if ($sisa == 0)
                                        <span
                                            class="inline-flex items-center justify-center px-2 py-1
                                                   text-[10px] sm:text-xs font-semibold
                                                   bg-green-100 text-green-700 rounded-full">

                                            Selesai
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center justify-center px-2 py-1
                                                   text-[10px] sm:text-xs font-semibold
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

        <div class="bg-white border rounded-xl shadow-sm p-4 sm:p-5">

            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-4">
                Bukti Peminjaman
            </h3>

            @if ($borrowImages->count())

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">

                    @foreach ($borrowImages as $image)
                        <div class="relative group cursor-pointer">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                class="rounded-lg object-cover w-full h-36 shadow">

                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-sm transition rounded-lg"
                                @click="imageSrc='{{ asset('storage/' . $image->image_path) }}'; preview=true">
                                Lihat Foto
                            </div>
                        </div>
                    @endforeach

                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    Belum ada foto bukti peminjaman
                </div>

            @endif

        </div>

        <div class="bg-white border rounded-xl shadow-sm p-4 sm:p-5">

            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-4">
                Bukti Pengembalian
            </h3>

            @if ($returnImages->count())

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">

                    @foreach ($returnImages as $image)
                        <div class="relative group cursor-pointer">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                class="rounded-lg object-cover w-full h-36 shadow">

                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-sm transition rounded-lg"
                                @click="imageSrc='{{ asset('storage/' . $image->image_path) }}'; preview=true">
                                Lihat Foto
                            </div>
                        </div>
                    @endforeach

                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    Belum ada foto bukti pengembalian
                </div>

            @endif

        </div>

        <!-- Riwayat Pengembalian -->
        <div class="p-3 sm:p-5 bg-white border rounded-xl shadow-sm overflow-hidden">

            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-4">
                Riwayat Pengembalian
            </h3>

            <div class="overflow-x-auto">

                <table class="min-w-[600px] w-full text-xs sm:text-sm text-gray-600">

                    <thead class="bg-gray-50 border-b text-gray-700">
                        <tr>

                            <th class="px-2 sm:px-4 py-3 font-medium text-left">
                                Tanggal
                            </th>

                            <th class="px-2 sm:px-4 py-3 font-medium text-left">
                                Alat
                            </th>

                            <th class="px-2 sm:px-4 py-3 font-medium text-center">
                                Jumlah
                            </th>

                            <th class="px-2 sm:px-4 py-3 font-medium hidden sm:table-cell text-left">
                                Kondisi
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($returnRecords as $log)
                            <tr class="border-b hover:bg-gray-50">

                                <td class="px-2 sm:px-4 py-3">
                                    {{ \Carbon\Carbon::parse($log->returned_at)->format('d M Y, H:i') }}
                                </td>

                                <td class="px-2 sm:px-4 py-3 font-medium text-gray-800">
                                    {{ $log->name }}
                                </td>

                                <td class="px-2 sm:px-4 py-3 text-center">
                                    {{ $log->quantity }}
                                </td>

                                <td class="px-2 sm:px-4 py-3 hidden sm:table-cell">
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

        <!-- Delete Box -->
        <div x-data="{ confirmDelete: false }" class="p-4 sm:p-6 bg-white rounded-xl shadow-sm border border-gray-100">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                <div class="min-w-[200px]">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Hapus Peminjaman
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Menghapus peminjaman juga akan menghapus semua history pengembalian.
                    </p>
                </div>

                <div class="p-2 bg-red-50 rounded-lg self-start sm:self-center">
                    <i class="ri-delete-bin-6-line text-red-500 text-xl"></i>
                </div>
            </div>

            <button @click="confirmDelete = true"
                class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2
                       bg-red-700 text-white rounded-lg hover:bg-red-600 transition font-medium">

                <i class="ri-delete-bin-line text-lg"></i>
                Hapus
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

                            <form method="POST" action="{{ route('loans.destroy', $loan->id) }}">
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

        <!-- Modal Pengembalian -->
        <div x-show="openReturnModal" x-cloak
            class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">

            <div @click.away="openReturnModal = false"
                class="bg-white w-full max-w-4xl rounded-2xl shadow-lg
                       p-3 sm:p-6 space-y-5
                       max-h-[90vh] overflow-y-auto">

                <!-- Header -->
                <div class="flex items-center justify-between border-b pb-3">

                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">
                        Pengembalian Barang
                    </h3>

                    <button @click="openReturnModal = false" class="text-gray-400 hover:text-gray-600">

                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('loans.return', $loan) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">

                    @csrf

                    <div class="divide-y divide-gray-100 border rounded-xl">

                        @foreach ($loan->items as $item)
                            @php
                                $sisa = $item->quantity - $item->returned_quantity;
                            @endphp

                            <div class="p-3 sm:p-4 flex flex-col lg:flex-row gap-4 lg:items-start">

                                <!-- Thumbnail -->
                                <div
                                    class="w-full lg:w-20 h-40 lg:h-20 rounded-xl overflow-hidden border bg-gray-100 shrink-0">

                                    @if ($item->tool->image_path)
                                        <img src="{{ Storage::url($item->tool->image_path) }}"
                                            alt="{{ $item->tool->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i data-lucide="image" class="w-6 h-6"></i>
                                        </div>
                                    @endif

                                </div>

                                <!-- Info -->
                                <div class="flex-1">

                                    <h4 class="font-medium text-gray-800 text-sm sm:text-base">
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

                                <!-- Form -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full lg:w-auto">

                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                            Jumlah
                                        </label>

                                        <input type="number" name="items[{{ $loop->index }}][quantity]"
                                            min="0" max="{{ $sisa }}" placeholder="0"
                                            class="w-full border-gray-300 rounded-lg text-sm">
                                    </div>

                                    <div class="min-w-[180px]">

                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                            Kondisi
                                        </label>

                                        <input type="text" name="items[{{ $loop->index }}][condition_on_return]"
                                            placeholder="Baik / Lecet / Rusak"
                                            class="w-full border-gray-300 rounded-lg text-sm">
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                    <!-- Upload Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Dokumentasi</label>
                        <x-input-multiple-files name="images[]"></x-input-multiple-files>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-3 border-t">

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

        <!-- PREVIEW IMAGE -->
        <template x-if="preview">
            <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
                <div class="relative max-w-4xl w-full px-4">
                    <button @click="preview=false"
                        class="absolute top-4 right-4 bg-white rounded-full p-2 shadow">✕</button>
                    <img :src="imageSrc" class="rounded-lg max-h-[90vh] mx-auto">
                </div>
            </div>
        </template>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                lucide.createIcons();
            });
        </script>

    </div>
</x-layouts.app>
