<x-layouts.app title="Peminjaman">

    <div class="">

        <form action="{{ route('loans.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="">
            @csrf

            <!-- Daftar Alat -->
            <div x-data="loanForm(@js($tools))"
                 class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">

                 <div class="flex items-center gap-4 mb-7 pb-5 border-b border-solid border-indigo-400">
                <div
                    class="w-16 h-16 rounded-2xl bg-white/20 flex items-center justify-center">
                    <i class="ri-archive-line text-3xl"></i>
                </div>

                <div>
                    <h1 class="text-2xl font-bold">
                        Form Peminjaman Alat
                    </h1>
                    <p class="mt-1">
                        Tambahkan alat yang ingin dipinjam beserta dokumentasi
                        peminjaman.
                    </p>
                </div>
            </div>
                 <div class="mb-6">

                     <h3
                        class="text-lg font-semibold mb-5 flex items-center gap-2">
                        <i class="ri-user-line text-indigo-600"></i>
                        Data Peminjam
                    </h3>
    
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Peminjam
                        </label>
    
                        <input type="text"
                               name="peminjam"
                               id="peminjam"
                               placeholder="Masukkan nama peminjam"
                               required
                               class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3">
                    </div>
                 </div>


                <div class="flex items-center justify-between mb-6">
                    <h3
                        class="text-lg font-semibold flex items-center gap-2">
                        <i class="ri-tools-line text-indigo-600"></i>
                        Daftar Alat
                    </h3>

                    <button type="button"
                            @click="add()"
                            :disabled="selectedTools().length >= tools.length"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition disabled:bg-gray-300">

                        <i class="ri-add-line"></i>
                        Tambah Alat
                    </button>
                </div>

                <div class="space-y-4">

                    <template x-for="(item, idx) in items" :key="idx">

                        <div
                            class="border border-gray-200 rounded-2xl p-5 bg-gray-50">

                            <div
                                class="flex items-center justify-between mb-4">

                                <div
                                    class="font-semibold text-gray-700">
                                    Item #<span x-text="idx + 1"></span>
                                </div>

                                <button type="button"
                                        @click="remove(idx)"
                                        :disabled="items.length <= 1"
                                        class="text-red-500 hover:text-red-600 disabled:text-gray-300">

                                    <i
                                        class="ri-delete-bin-line text-xl"></i>
                                </button>
                            </div>

                            <div
                                class="grid md:grid-cols-3 gap-4">

                                <!-- Alat -->
                                <div class="md:col-span-2">

                                    <label
                                        class="block text-sm font-medium mb-2">
                                        Pilih Alat
                                    </label>

                                    <select
                                        :name="`items[${idx}][tool_id]`"
                                        x-model="item.tool_id"
                                        required
                                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                                        <option value="">
                                            Pilih Alat
                                        </option>

                                        @foreach ($tools as $t)
                                            <option
                                                value="{{ $t->id }}">
                                                {{ $t->name }}
                                                (Stok:
                                                {{ $t->stock }})
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <!-- Jumlah -->
                                <div>

                                    <label
                                        class="block text-sm font-medium mb-2">
                                        Jumlah
                                    </label>

                                    <input type="number"
                                           min="1"
                                           required
                                           :name="`items[${idx}][quantity]`"
                                           x-model.number="item.quantity"
                                           class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                            </div>

                        </div>

                    </template>

                </div>

                <!-- Upload Foto -->
                <div class="mt-8">
                    <h3
                        class="text-lg font-semibold flex items-center gap-2 mb-4">
                        <i class="ri-image-line text-indigo-600"></i>
                        Bukti Peminjaman
                    </h3>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Dokumentasi</label>
                        <x-input-multiple-files name="images[]"></x-input-multiple-files>
                </div>

                <!-- Catatan -->
                <div class="mt-8">

                    <label
                        class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>

                    <textarea name="notes"
                              rows="5"
                              placeholder="Tambahkan catatan jika diperlukan..."
                              class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>

                </div>

                <!-- Footer -->
                <div
                    class="flex flex-col sm:flex-row justify-end gap-3 mt-8 pt-6 border-t">

                    <a href="{{ route('loans.index') }}"
                       class="px-5 py-3 rounded-xl border text-center hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                            class="px-6 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition">

                        <i class="ri-check-line"></i>
                        Simpan Peminjaman
                    </button>

                </div>

            </div>

        </form>

    </div>

    <script>
        function loanForm(tools) {
            return {
                tools,

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
                },

                selectedTools() {
                    return this.items
                        .map(i => i.tool_id)
                        .filter(Boolean)
                }
            }
        }
    </script>

</x-layouts.app>