@props(['name' => 'images[]'])

<div 
    x-data="{ 
        files: [], 
        handleDrop(e) {
            const droppedFiles = Array.from(e.dataTransfer.files);
            this.files = [...this.files, ...droppedFiles];
            this.$refs.fileInput.files = e.dataTransfer.files;
        } 
    }"
    class="flex flex-col gap-2 items-center border-2 border-dashed border-gray-300 p-6 rounded-xl cursor-pointer group hover:border-blue-500"
    @click="$refs.fileInput.click()"
    @dragover.prevent 
    @drop.prevent="handleDrop($event)"
>
    <i class="ri-image-add-line text-4xl text-gray-400 group-hover:text-blue-500 mb-3"></i>
    <p class="text-gray-500 group-hover:text-blue-600 text-sm">Klik atau seret gambar ke sini</p>

    <input 
        type="file" 
        name="{{ $name }}" 
        x-ref="fileInput" 
        multiple 
        class="hidden" 
        accept="image/*" 
        @change="files = [...files, ...Array.from($event.target.files)]"
    />

    <div class="grid grid-cols-3 gap-3 mt-4">
        <template x-for="(file, index) in files" :key="index">
            <div class="relative group">
                <img :src="URL.createObjectURL(file)" class="w-full h-auto object-cover rounded-lg shadow-sm">
                <button 
                    type="button" 
                    class="absolute top-1 right-1 bg-white/80 hover:bg-red-500 hover:text-white rounded-full p-1 transition"
                    @click.stop="files.splice(index, 1)"
                >
                    ✕
                </button>
            </div>
        </template>
    </div>
</div>
