<x-layouts.app title="Project Patrol">
    <div class="min-h-screen bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="max-w-7xl mx-auto">

            <!-- HEADER -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Daftar Project</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Pantau progress, status, dan detail project yang sedang berjalan.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <a href="{{ route('project.create') }}"
                        class="flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                        <i class="ri-add-line"></i> Project Baru
                    </a>

                    <form action="{{ route('project.index') }}" method="get" class="relative w-full sm:w-56">
                        <input name="search" type="text" placeholder="Cari project..."
                            value="{{ request()->search }}"
                            class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                        <i class="ri-search-line absolute left-3 top-2 text-gray-400"></i>
                    </form>
                </div>
            </div>

            <!-- FILTER & SORT -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">

                <div class="flex flex-wrap gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'semua']) }}"
                        class="px-3 py-1.5 text-sm rounded-lg border text-gray-600 hover:border-indigo-400 hover:text-indigo-600">
                        Semua
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'Selesai']) }}"
                        class="px-3 py-1.5 text-sm rounded-lg bg-green-50 text-green-700 border border-green-200">
                        Selesai
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'Berjalan']) }}"
                        class="px-3 py-1.5 text-sm rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
                        Berjalan
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'Dihentikan']) }}"
                        class="px-3 py-1.5 text-sm rounded-lg bg-yellow-50 text-yellow-700 border border-yellow-200">
                        Dihentikan
                    </a>
                </div>

                <select onchange="location.href=this.value"
                    class="w-full md:w-auto border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-indigo-500">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'terbaru']) }}" @if(request()->sort == 'terbaru') selected @endif>Urutkan: Terbaru</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'terlama']) }}" @if(request()->sort == 'terlama') selected @endif>Urutkan: Terlama</option>
                </select>
            </div>

            <!-- TABLE DESKTOP -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm border overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr class="text-gray-600 text-left">
                            <th class="px-6 py-3 font-medium">Judul Project</th>
                            <th class="px-6 py-3 font-medium">Lokasi</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium">Tanggal Mulai</th>
                            <th class="px-6 py-3 text-center font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($datas as $project)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">{{ $project->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $project->lokasi }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $project->lokasi }}</td>
                                <td class="px-6 py-4">
                                    @if ($project->status === 'Selesai')
                                        <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                            Selesai
                                        </span>
                                    @elseif ($project->status === 'Berjalan')
                                        <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                                            Berjalan
                                        </span>
                                    @else
                                        <span class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">
                                            Tertunda
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('project.show', $project->id) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-sm text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                                        <i class="ri-eye-line"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                                    Belum ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- MOBILE CARD -->
            <div class="md:hidden space-y-4">
                @forelse ($datas as $project)
                    <div class="border rounded-xl p-4 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $project->nama }}</h3>
                                <p class="text-xs text-gray-500">{{ $project->lokasi }}</p>
                            </div>

                            @if ($project->status === 'Selesai')
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Selesai</span>
                            @elseif ($project->status === 'Berjalan')
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Berjalan</span>
                            @else
                                <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">Tertunda</span>
                            @endif
                        </div>

                        <div class="mt-2 text-sm text-gray-600">
                            Mulai: {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d M Y') }}
                        </div>

                        <a href="{{ route('project.show', $project->id) }}"
                            class="mt-3 flex justify-center items-center gap-2 w-full px-4 py-2 text-sm text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                            <i class="ri-eye-line"></i> Detail
                        </a>
                    </div>
                @empty
                    <p class="text-center text-sm text-gray-500">
                        Belum ada data project
                    </p>
                @endforelse
            </div>

            <!-- FOOTER -->
            <div class="text-xs text-gray-400 mt-6 text-center">
                Diperbarui terakhir: {{ now()->format('d M Y, H:i') }}
            </div>

        </div>
    </div>
</x-layouts.app>
