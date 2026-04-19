@props([
    'name' => 'images[]',
    'existing' => [], // 🔥 gambar lama
])

<div x-data="{
    files: [],
    existingImages: {{ json_encode($existing) }},

    removeExisting(index) {
        this.existingImages.splice(index, 1);
    },

    handleDrop(e) {
        const droppedFiles = Array.from(e.dataTransfer.files);
        this.files = [...this.files, ...droppedFiles];

        const dataTransfer = new DataTransfer();
        this.files.forEach(file => dataTransfer.items.add(file));
        this.$refs.fileInput.files = dataTransfer.files;
    }
}"
    class="flex flex-col gap-2 items-center border-2 border-dashed border-gray-300 p-6 rounded-xl cursor-pointer group hover:border-blue-500"
    @click="$refs.fileInput.click()" @dragover.prevent @drop.prevent="handleDrop($event)">
    <i class="ri-image-add-line text-4xl text-gray-400 group-hover:text-blue-500 mb-3"></i>
    <p class="text-gray-500 group-hover:text-blue-600 text-sm">
        Klik atau seret gambar ke sini
    </p>

    <input type="file" name="{{ $name }}" x-ref="fileInput" multiple class="hidden" accept="image/*"
        @change="files = [...files, ...Array.from($event.target.files)]" />

    <!-- 🔹 GAMBAR LAMA -->
    <div class="grid grid-cols-3 gap-3 mt-4 w-full">
        <template x-for="(img, index) in existingImages" :key="'old-' + index">
            <div class="relative">
                <img :src="`{{ asset('storage') }}/${img.image_url}`" class="w-full h-auto object-cover rounded-lg shadow-sm">

                <button type="button"
                    class="absolute top-1 right-1 bg-white/80 hover:bg-red-500 hover:text-white rounded-full p-1"
                    @click.stop="removeExisting(index)">
                    ✕
                </button>

                <!-- kirim ke backend -->
                <input type="hidden" name="existing_images[]" :value="img.id">
            </div>
        </template>
    </div>

    <!-- 🔹 GAMBAR BARU -->
    <div class="grid grid-cols-3 gap-3 mt-4 w-full">
        <template x-for="(file, index) in files" :key="'new-' + index">
            <div class="relative">
                <img :src="URL.createObjectURL(file)" class="w-full h-auto object-cover rounded-lg shadow-sm">

                <button type="button"
                    class="absolute top-1 right-1 bg-white/80 hover:bg-red-500 hover:text-white rounded-full p-1"
                    @click.stop="files.splice(index, 1)">
                    ✕
                </button>
            </div>
        </template>
    </div>
</div>
