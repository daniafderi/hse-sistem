<x-layouts.app title="Tambah Project">
    @if (session('success'))
        {{ session('success') }}
    @endif

    <section class="min-h-screen">
        <div class="mx-auto bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3 flex items-center">
                <i class="ri-clipboard-line text-indigo-600 text-2xl mr-2"></i>
                Project Baru
            </h2>

            <form action="{{ route('project.store') }}" method="post" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('POST')

                <!-- Input Baris Pertama -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="nama">Judul Project <span
                                class="text-red-500">*</span></label>
                        <input name="nama" id="nama" type="text" placeholder="Masukkan judul project"
                            class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="lokasi">Lokasi <span
                                class="text-red-500">*</span></label>
                        <input type="text" placeholder="Masukkan lokasi project" name="lokasi" id="lokasi"
                            class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="status">Status <span
                                class="text-red-500">*</span></label>
                        <select
                            class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm"
                            name="status" id="status">
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="deskripsi">Deskripsi
                        Project</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" placeholder="Tuliskan deskripsi singkat project"
                        class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm resize-none"></textarea>
                </div>

                <!-- Date -->

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="tanggal_mulai">Tanggal
                            Mulai</label>
                        <input id="tanggal_mulai" name="tanggal_mulai" type="date"
                            class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="tanggal_selesai">Target
                            Selesai</label>
                        <input id="tanggal_selesai" name="tanggal_selesai" type="date"
                            class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                    </div>

                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end pt-4 gap-2.5">
                    <a href="{{ route('project.index') }}" class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 px-4 py-2 rounded-lg text-sm shadow-sm transition">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md transition">
                        <i class="ri-send-plane-line mr-2"></i> Submit
                    </button>
                </div>

            </form>
        </div>
    </section>


    </x-layouts>
