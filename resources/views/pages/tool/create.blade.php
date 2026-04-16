<x-layouts.app title="Tambah Alat Baru">
    <div class="bg-white p-8 rounded-lg shadow-lg border">
        <h2 class="text-2xl font-semibold mb-6 pb-3 border-b text-gray-800 flex items-center gap-2">
            <i class="ri-tools-line text-blue-600 text-2xl"></i>
            Tambah Alat Baru
        </h2>

        <form action="{{ route('tools.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Nama Alat -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Alat <span class="text-red-500">*</span></label>
                    <input name="name" value="{{ old('name') }}" type="text" placeholder="Contoh: Helm Safety"
                        class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm"
                        required>
                </div>

                <!-- Stok -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
                    <input name="stock" type="number" min="0" value="{{ old('stock', 0) }}" placeholder="0"
                        class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm"
                        required>
                </div>

                <!-- Stok Minimum -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Kebutuhan APD <span class="text-red-500">*</span></label>
                    <input name="stock_minimum" type="number" min="0" value="{{ old('stock_minimum', 0) }}" placeholder="0"
                        class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm"
                        required>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end pt-4 gap-2.5">
                <a href="{{ route('tools.index') }}"
                    class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 px-4 py-2 rounded-lg text-sm shadow-sm transition">
                    Batal
                </a>

                <button type="submit"
                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition">
                    <i class="ri-send-plane-line mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>
