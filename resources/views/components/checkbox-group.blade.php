@props([
    'name' => 'users',
    'options' => [],
    'currentUser' => null,
    'selected' => [], // 🔥 TAMBAHKAN INI
    'label' => 'Pilih Kontributor'
])

<div 
    x-data="{
    showModal: false,

    users: @js($options),

    selectedIds: @js($selected),

    contributors: [],

    init() {
        // 🔥 mapping selected ID ke object user
        this.contributors = this.users
            .filter(user => this.selectedIds.includes(user.id))
            .map(user => ({
                id: user.id,
                name: user.name,
                isOwner: user.id === {{ $currentUser->id }}
            }));

        // 🔥 pastikan current user selalu ada
        if (!this.contributors.find(c => c.id === {{ $currentUser->id }})) {
            this.contributors.unshift({
                id: {{ $currentUser->id }},
                name: '{{ $currentUser->name }}',
                isOwner: true
            });
        }
    },

    toggleUser(user) {
        if (!this.contributors.find(c => c.id === user.id)) {
            this.contributors.push({ id: user.id, name: user.name, isOwner: false });
        }
    },

    removeUser(id) {
        this.contributors = this.contributors.filter(c => c.id !== id || c.isOwner);
    }
}"
x-init="init()"
    class="space-y-3"
>
    <label class="block text-sm font-semibold text-gray-700">{{ $label }}</label>

    {{-- Daftar kontributor terpilih --}}
    <div class="flex flex-wrap gap-2">
        <template x-for="contributor in contributors" :key="contributor.id">
            <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-300 px-3 py-1 rounded-full">
                <span class="text-sm font-medium text-indigo-800" x-text="contributor.name"></span>

                <template x-if="!contributor.isOwner">
                    <button type="button" @click="removeUser(contributor.id)" class="text-red-500 hover:text-red-700">
                        &times;
                    </button>
                </template>

                <template x-if="contributor.isOwner">
                    <span class="text-xs text-gray-500">(Anda)</span>
                </template>
            </div>
        </template>
    </div>

    {{-- Hidden input untuk dikirim ke server --}}
    <template x-for="c in contributors" hidden>
        <input type="hidden" name="{{ $name }}[]" :value="c.id">
    </template>

    {{-- Tombol Tambah --}}
    <button type="button" @click="showModal = true" class="mt-2 inline-flex items-center gap-2 px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10 5a1 1 0 011 1v3h3a1 1 0 010 2h-3v3a1 1 0 01-2 0v-3H6a1 1 0 010-2h3V6a1 1 0 011-1z" />
        </svg>
        Tambah Anggota
    </button>

    {{-- Modal daftar user --}}
    <div x-data="{search: ''}" x-show="showModal" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div @click.away="showModal = false" class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md space-y-3">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Pilih Kontributor</h3>
            <input type="text" placeholder="Cari nama..."
                class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm mb-3"
                x-model="search">

            <div class="max-h-60 overflow-y-auto space-y-2">
                <template x-for="user in users" :key="user.id">
                    <div class="flex items-center justify-between p-2 border rounded-lg hover:bg-indigo-50 cursor-pointer"
                         @click="toggleUser(user)">
                        <span class="text-sm font-medium text-gray-700" x-text="user.name"></span>
                        <template x-if="contributors.find(c => c.id === user.id)">
                            <span class="text-indigo-600 text-xs font-medium">Dipilih</span>
                        </template>
                    </div>
                </template>
            </div>

            <div class="text-right pt-3">
                <button type="button" @click="showModal = false" class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">Tutup</button>
            </div>
        </div>
    </div>
</div>
