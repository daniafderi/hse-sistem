<x-layouts.app title="Safety Briefing">
    <div class="bg-white min-h-screen p-4 sm:p-6 rounded-lg shadow-md">
        <div class="max-w-7xl mx-auto space-y-5">

            <!-- ================= HEADER ================= -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">
                        Daftar Safety Briefing
                    </h1>
                    <p class="text-gray-500 text-sm mt-1">
                        Pantau kegiatan briefing keselamatan di berbagai lokasi kerja
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <a href="{{ route('safety-briefing.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                        <i class="ri-add-line mr-1"></i> Briefing Baru
                    </a>

                    <form action="{{ route('safety-briefing.index') }}" method="get" class="relative">
                        <i class="ri-search-line absolute left-3 top-2 text-gray-400"></i>
                        <input name="search" type="text" placeholder="Cari briefing..."
                            value="{{ request()->search }}"
                            class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-56">
                    </form>
                </div>
            </div>

            <!-- ================= FILTER ================= -->
            <div class="flex justify-between">
                <a href="{{ route('download.template', ['file' => 'safety-briefing.pdf']) }}" download
                    class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    <i class="ri-download-2-line text-xs"></i>
                    Download Form
                </a>
                <select onchange="location.href=this.value"
                    class="w-full sm:w-auto border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'terbaru']) }}"
                        {{ request('sort') == 'terbaru' ? 'selected' : '' }}>
                        Urutkan : Terbaru
                    </option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'terlama']) }}"
                        {{ request('sort') == 'terlama' ? 'selected' : '' }}>
                        Urutkan : Terlama
                    </option>
                </select>
            </div>

            <!-- ================= MOBILE CARD ================= -->
            <div class="grid grid-cols-1 gap-4 md:hidden">
                @forelse($datas as $brief)
                    <div class="border rounded-xl p-4 shadow-sm space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs text-gray-500">#{{ $brief->id }}</p>
                                <h3 class="font-semibold text-gray-800">{{ $brief->tempat }}</h3>
                                <p class="text-sm text-gray-600">{{ $brief->pekerjaan }}</p>
                            </div>
                            <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-1 rounded-full">
                                {{ $brief->jumlah_peserta }} Orang
                            </span>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <div
                                class="h-8 w-8 rounded-full bg-indigo-500 text-white flex items-center justify-center font-semibold">
                                {{ strtoupper(substr($brief->user->name, 0, 1)) }}
                            </div>
                            <span class="text-gray-700">{{ $brief->user->name }}</span>
                        </div>

                        <div class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($brief->created_at)->format('d M Y, H:i') }}
                        </div>

                        <a href="{{ route('safety-briefing.show', $brief->id) }}"
                            class="inline-flex items-center justify-center w-full gap-1 px-3 py-2 text-sm font-medium text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                            <i class="ri-eye-line"></i> Detail
                        </a>
                    </div>
                @empty
                    <p class="text-center text-sm text-gray-500">Belum ada data</p>
                @endforelse
            </div>

            <!-- ================= TABLE DESKTOP ================= -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm border overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-600 font-medium">
                        <tr>
                            <th class="px-6 py-3 text-left">ID</th>
                            <th class="px-6 py-3 text-left">Tempat</th>
                            <th class="px-6 py-3 text-left">Pekerjaan</th>
                            <th class="px-6 py-3 text-center">Peserta</th>
                            <th class="px-6 py-3 text-center">Safety</th>
                            <th class="px-6 py-3 text-center">Tanggal</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($datas as $brief)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-500">#{{ $brief->id }}</td>
                                <td class="px-6 py-4 font-medium">{{ $brief->tempat }}</td>
                                <td class="px-6 py-4">{{ $brief->pekerjaan }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs">
                                        {{ $brief->jumlah_peserta }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        <div
                                            class="h-8 w-8 rounded-full bg-indigo-500 text-white flex items-center justify-center font-semibold">
                                            {{ strtoupper(substr($brief->user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600">
                                    {{ \Carbon\Carbon::parse($brief->created_at)->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('safety-briefing.show', $brief->id) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                                        <i class="ri-eye-line"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-layouts.app>
