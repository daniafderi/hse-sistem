<x-layouts.app title="Detail Briefing">
    <div class="min-h-screen py-6" x-data="{ preview: false, imageSrc: '', openModalUpdate: false, confirmDelete: false }">

        <div class="max-w-5xl mx-auto space-y-6">

            <!-- HEADER -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">
                        Detail Safety Briefing
                    </h1>
                    <p class="text-sm text-gray-500">
                        Informasi lengkap kegiatan briefing
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2">
                    <button @click="openModalUpdate = true"
                        class="flex items-center justify-center gap-2 bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow hover:bg-blue-600 transition">
                        <i class="ri-refresh-line"></i> Update
                    </button>

                    <a href="{{ route('safety-briefing.index') }}"
                        class="flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:text-indigo-600 hover:border-indigo-400 transition">
                        <i class="ri-arrow-left-line"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- INFORMASI UMUM -->
            <div class="bg-white rounded-xl shadow-sm border p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Informasi Umum</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Tempat</p>
                        <p class="font-medium">{{ $safetyBriefing->tempat }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Pekerjaan</p>
                        <p class="font-medium">{{ $safetyBriefing->pekerjaan }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal & Waktu</p>
                        <p class="font-medium">{{ $safetyBriefing->created_at }} WIB</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Peserta</p>
                        <p class="font-medium">{{ $safetyBriefing->jumlah_peserta }} Orang</p>
                    </div>
                </div>
            </div>

            <!-- SAFETY LAPANGAN -->
            <div class="bg-white rounded-xl shadow-sm border p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">
                    Safety Lapangan
                </h2>

                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-full bg-indigo-500 text-white flex items-center justify-center font-semibold">
                        {{ strtoupper(substr($safetyBriefing->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium">{{ $safetyBriefing->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $safetyBriefing->user->role }}</p>
                    </div>
                </div>
            </div>

            <!-- CATATAN -->
            <div class="bg-white rounded-xl shadow-sm border p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-3">
                    Catatan / Hasil Diskusi
                </h2>
                <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
                    {{ $safetyBriefing->catatan }}
                </p>
            </div>

            <!-- DOKUMENTASI -->
            <div class="bg-white rounded-xl shadow-sm border p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">
                        Dokumentasi Kegiatan
                    </h2>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                    @forelse ($safetyBriefing->images as $image)
                        <div class="relative group cursor-pointer">
                            <img src="{{ asset('storage/' . $image->image_url) }}"
                                class="rounded-lg object-cover w-full h-36 shadow">

                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-sm transition rounded-lg"
                                @click="imageSrc='{{ asset('storage/' . $image->image_url) }}'; preview=true">
                                Lihat Foto
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic col-span-full">
                            Tidak ada dokumentasi
                        </p>
                    @endforelse
                </div>
            </div>

            <!-- HAPUS -->
            <div class="bg-white rounded-xl shadow-sm border p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">
                    Hapus Briefing
                </h2>
                <p class="text-sm text-gray-500 mb-4">
                    Data akan dihapus permanen
                </p>

                <button @click="confirmDelete = true"
                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="ri-delete-bin-line"></i> Hapus
                </button>
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

        <!-- MODAL DELETE -->
        <div x-show="confirmDelete" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-sm">
                <h3 class="font-semibold text-lg">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-500 mt-2 mb-4">
                    Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex justify-end gap-2">
                    <button @click="confirmDelete=false" class="px-4 py-2 border rounded-lg">Batal</button>

                    <form method="POST" action="{{ route('safety-briefing.destroy', $safetyBriefing->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL UPDATE -->
        <div x-show="openModalUpdate" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50">
            <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-lg p-8 h-[-webkit-fill-available] overflow-y-scroll">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3 flex items-center">
                    <i class="ri-clipboard-line text-indigo-600 text-2xl mr-2"></i>
                    Perbarui Laporan Briefing
                </h2>

                <form class="space-y-8" action="{{ route('safety-briefing.update', $safetyBriefing->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <!-- Input Baris Pertama -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="tempat">Tempat <span
                                    class="text-red-500">*</span></label>
                            <input name="tempat" id="tempat" type="text" placeholder="Masukkan lokasi briefing"
                                class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm" value="{{ $safetyBriefing->tempat }}"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="pekerjaan">Pekerjaan <span
                                    class="text-red-500">*</span></label>
                            <input name="pekerjaan" id="pekerjaan" type="text" placeholder="Masukkan nama pekerjaan"
                                class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm" value="{{ $safetyBriefing->pekerjaan }}"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="jumlah_peserta">Jumlah
                                Peserta <span class="text-red-500">*</span></label>
                            <input id="jumlah_peserta" name="jumlah_peserta" type="number" min="0"
                                placeholder="0"
                                class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm" value="{{ $safetyBriefing->jumlah_peserta }}"
                                required>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Briefing <span
                                class="text-red-500">*</span></label>
                        <textarea name="catatan" rows="4" placeholder="Tuliskan poin penting briefing hari ini..."
                            class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm resize-none">{{ $safetyBriefing->catatan }}</textarea>
                    </div>

                    <!-- Upload Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Dokumentasi</label>
                        <x-input-multiple-files name="new_photos[]" :existing="$safetyBriefing->images"/>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-end pt-4 gap-2.5">
                        <button @click="openModalUpdate=false"
                            class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-blue-600 hover:border-blue-400 px-4 py-2 rounded-lg text-sm shadow-sm transition" type="button">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition">
                            <i class="ri-send-plane-line mr-2"></i> Submit
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</x-layouts.app>
