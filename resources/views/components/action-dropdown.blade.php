<div x-data="dropdownMenu()" class="relative">

    <!-- Trigger -->
    <button @click="toggle"
        class="px-3 py-2 rounded-md border bg-white shadow-sm hover:shadow-md flex items-center gap-1 text-sm">
        <i class="ri-more-2-fill text-lg"></i>
    </button>

    <!-- Teleport -->
    <template x-teleport="body">
        <div x-show="open" x-transition @click.outside="close"
            :style="`position: absolute; left:${left}px; top:${top}px;`"
            class="w-44 bg-white border rounded-xl shadow-lg py-2 z-[9999] text-gray-600 text-sm" x-cloak>

            <!-- Lihat -->
            <a href="{{ route('daily-report.show', $data->id) }}"
                class="flex items-center gap-2 px-4 py-2 hover:bg-gray-50">
                <i class="ri-eye-line text-base"></i> Lihat
            </a>

            <!-- Edit -->
            <a href="{{ route('daily-report.edit', $data->id) }}"
                class="flex items-center gap-2 px-4 py-2 hover:bg-gray-50">
                <i class="ri-edit-line text-base"></i> Edit
            </a>

            <!-- Validasi -->
            <button onclick="Livewire.dispatch('openValidationModal', { id: {{ $data->id }} })"
                class="flex items-center w-full text-left gap-2 px-4 py-2 hover:bg-gray-50">
                <i class="ri-shield-check-line text-base"></i> Validasi
            </button>

            <!-- Hapus -->
            <form method="POST" action="{{ route('daily-report.destroy', $data->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Yakin ingin menghapus?')"
                    class="flex items-center w-full text-left gap-2 px-4 py-2 hover:bg-red-50 text-red-600">
                    <i class="ri-delete-bin-2-line text-base"></i> Hapus
                </button>
            </form>
        </div>
    </template>

</div>
