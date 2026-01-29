<x-layouts.app title="Detail Briefing">
    <div class="min-h-screen px-4 sm:px-6 py-6"
        x-data="{ preview:false, imageSrc:'', openModalUpdate:false, confirmDelete:false }">

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
                        {{ strtoupper(substr($safetyBriefing->user->name,0,1)) }}
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
                            <img src="{{ asset('storage/'.$image->image_url) }}"
                                class="rounded-lg object-cover w-full h-36 shadow">

                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-sm transition rounded-lg"
                                @click="imageSrc='{{ asset('storage/'.$image->image_url) }}'; preview=true">
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
                    <button @click="confirmDelete=false"
                        class="px-4 py-2 border rounded-lg">Batal</button>

                    <form method="POST"
                        action="{{ route('safety-briefing.destroy',$safetyBriefing->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
