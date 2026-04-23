<x-layouts.app title="Peminjaman">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2">
            <i class="ri-archive-line text-indigo-600"></i>
            Form Peminjaman
        </h2>

        <form action="{{ route('loans.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Peminjam -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Peminjam</label>
                <input type="text" name="peminjam" id="peminjam" placeholder="Nama Peminjam"
                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg p-3 shadow-sm">
            </div>

            <!-- Daftar Item -->
            <div x-data="loanForm()" class="space-y-3">
                <template x-for="(item, idx) in items" :key="idx">
                    <div
                        class="grid grid-cols-12 gap-3 bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm relative">

                        <!-- Select Alat -->
                        <select :name="`items[${idx}][tool_id]`" x-model="item.tool_id"
                            class="col-span-6 border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                            <option value="">— Pilih Alat —</option>
                            @foreach ($tools as $t)
                                <option value="{{ $t->id }}">{{ $t->name }} — Stok: {{ $t->stock }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Input Jumlah -->
                        <input type="number" :name="`items[${idx}][quantity]`" x-model.number="item.quantity"
                            min="1"
                            class="col-span-3 border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Jumlah" required>

                        <!-- Tombol Hapus -->
                        <button type="button" @click="remove(idx)"
                            :disabled="items.length <= 1"
                            class="col-span-3 flex items-center justify-center px-3 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                            <i class="ri-delete-bin-line text-lg"></i>
                            <span class="ml-1">Hapus</span>
                        </button>
                    </div>
                </template>

                <!-- Tombol Tambah Item -->
                <button type="button" @click="add()"
                    class="px-4 py-2 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded-lg font-medium transition">
                    + Tambah Item
                </button>

                <!-- Catatan -->
                <div>
                    <textarea name="notes" placeholder="Catatan peminjaman"
                        class="w-full border-gray-300 rounded-lg p-3 min-h-[100px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"></textarea>
                </div>

                <!-- Aksi -->
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('loans.index') }}"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
                        Batal
                    </a>
                    <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow transition">
                        Pinjam
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function loanForm() {
            return {
                items: [{
                    tool_id: '{{ request('tool_id') ?? '' }}',
                    quantity: 1
                }],
                add() {
                    this.items.push({
                        tool_id: '',
                        quantity: 1
                    })
                },
                remove(i) {
                    this.items.splice(i, 1)
                }
            }
        }
    </script>

</x-layouts.app>
