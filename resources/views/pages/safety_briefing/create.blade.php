<x-layouts.app title="Tambah Safety Briefing">
    <section class="min-h-screen">
        <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3 flex items-center">
                <i class="ri-clipboard-line text-indigo-600 text-2xl mr-2"></i>
                Laporan Briefing Baru
            </h2>

            <form class="space-y-8" action="{{ route('safety-briefing.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <!-- Input Baris Pertama -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="tempat">Tempat <span
                                class="text-red-500">*</span></label>
                        <input name="tempat" id="tempat" type="text" placeholder="Masukkan lokasi briefing"
                            class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="pekerjaan">Pekerjaan <span
                                class="text-red-500">*</span></label>
                        <input name="pekerjaan" id="pekerjaan" type="text" placeholder="Masukkan nama pekerjaan"
                            class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="jumlah_peserta">Jumlah Peserta <span
                                class="text-red-500">*</span></label>
                        <input id="jumlah_peserta" name="jumlah_peserta" type="number" min="0" placeholder="0"
                            class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Briefing</label>
                    <textarea name="catatan" rows="4" placeholder="Tuliskan poin penting briefing hari ini..."
                        class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm resize-none"></textarea>
                </div>

                <!-- Upload Foto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Dokumentasi</label>
                        <x-input-multiple-files name="images[]"></x-input-multiple-files>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end pt-4 gap-2.5">
                    <a href="{{ route('safety-briefing.index') }}" class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-blue-600 hover:border-blue-400 px-4 py-2 rounded-lg text-sm shadow-sm transition">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition">
                        <i class="ri-send-plane-line mr-2"></i> Submit
                    </button>
                </div>

            </form>
        </div>
    </section>

</x-layouts.app>
