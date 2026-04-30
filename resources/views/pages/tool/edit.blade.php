<x-layouts.app title="Edit Alat">
    <div class="bg-white p-8 rounded-lg shadow-lg border">
        <h2 class="text-2xl font-semibold mb-6 pb-3 border-b text-gray-800 flex items-center gap-2">
            <i class="ri-tools-line text-blue-600 text-2xl"></i>
            Edit Alat
        </h2>

        <form action="{{ route('tools.update', $tool) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Nama Alat -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Alat <span
                            class="text-red-500">*</span></label>
                    <input name="name" value="{{ old('name', $tool->name) }}" type="text"
                        placeholder="Contoh: Helm Safety"
                        class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm"
                        required>
                </div>

                <!-- Stok -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok <span
                            class="text-red-500">*</span></label>
                    <input name="stock" type="number" min="0" value="{{ old('stock', $tool->stock) }}"
                        placeholder="0"
                        class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm"
                        required>
                </div>

                <!-- Stok Minimum -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Kebutuhan APD Pertahun<span
                            class="text-red-500">*</span></label>
                    <input name="stock_minimum" type="number" min="0"
                        value="{{ old('stock_minimum', $tool->stock_minimum) }}" placeholder="0"
                        class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm"
                        required>
                </div>

            </div>

            <div x-data="{
                preview: '{{ $tool->image_path ? asset('storage/' . $tool->image_path) : '' }}'
            }" class="md:col-span-2 mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Gambar Alat
                </label>

                <div class="flex items-center justify-center w-full">
                    <label
                        class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-xl cursor-pointer
                   hover:border-indigo-400 hover:bg-indigo-50 transition relative overflow-hidden">

                        <!-- Preview (gambar lama / baru) -->
                        <template x-if="preview">
                            <img :src="preview" class="absolute inset-0 w-auto h-full object-cover rounded-xl">
                        </template>

                        <!-- Placeholder -->
                        <div x-show="!preview" class="flex flex-col items-center justify-center text-gray-400">
                            <i class="ri-image-line text-3xl mb-2"></i>
                            <p class="text-sm">Klik untuk upload gambar</p>
                            <p class="text-xs text-gray-400">PNG, JPG (max 2MB)</p>
                        </div>

                        <!-- Input -->
                        <input type="file" name="image_path" accept="image/*" class="hidden"
                            @change="preview = URL.createObjectURL($event.target.files[0])">
                    </label>
                </div>

                @error('image_path')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
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
