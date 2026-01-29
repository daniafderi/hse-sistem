<x-layouts.app title="Lapor Harian Safety Patrol">
    <div class="min-h-screen bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="max-w-7xl mx-auto">

            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">
                        Lapor Harian Safety Patrol
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Data laporan harian safety patrol.
                    </p>
                </div>

                <!-- Action -->
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <a href="{{ route('daily-report.create') }}"
                        class="flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                        <i class="ri-add-line"></i> Laporan Baru
                    </a>

                    <div class="relative w-full sm:w-56">
                        <input type="text" placeholder="Cari laporan..."
                            class="w-full pl-9 pr-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <i class="ri-search-line absolute left-3 top-2.5 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Filter & Sort -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">

                <!-- Filter Scroll -->
                <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-thin">
                    <button
                        class="whitespace-nowrap border border-gray-300 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 px-3 py-1.5 rounded-lg text-sm transition">
                        Semua
                    </button>
                    <button class="whitespace-nowrap bg-green-50 text-green-700 border border-green-200 px-3 py-1.5 rounded-lg text-sm">
                        Divalidasi
                    </button>
                    <button class="whitespace-nowrap bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-lg text-sm">
                        Menunggu
                    </button>
                    <button class="whitespace-nowrap bg-yellow-50 text-yellow-700 border border-yellow-200 px-3 py-1.5 rounded-lg text-sm">
                        Revisi
                    </button>
                    <button class="whitespace-nowrap bg-red-50 text-red-700 border border-red-200 px-3 py-1.5 rounded-lg text-sm">
                        Ditolak
                    </button>
                </div>

                <select
                    class="border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-auto">
                    <option>Urutkan: Terbaru</option>
                    <option>Urutkan: Tertua</option>
                    <option>Urutkan: Status</option>
                </select>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border overflow-x-auto">
                <table class="min-w-[1100px] w-full divide-y divide-gray-200 text-sm whitespace-nowrap">
                    <thead class="bg-gray-100">
                        <tr class="text-gray-600 text-left">
                            <th class="px-6 py-3 font-medium">Project</th>
                            <th class="px-6 py-3 font-medium">Permit</th>
                            <th class="px-6 py-3 font-medium">Pekerja</th>
                            <th class="px-6 py-3 font-medium">Jam Kerja</th>
                            <th class="px-6 py-3 font-medium">Safety</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                            <th class="px-6 py-3 text-right font-medium">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($datas as $data)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <a href="{{ route('daily-report.show', $data->id) }}">
                                    <div class="font-semibold text-gray-800">{{ $data->project->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $data->project->lokasi }}</div>
                                    </a>
                                </td>

                                <td class="px-6 py-4">{{ $data->permit }}</td>
                                <td class="px-6 py-4">{{ $data->jumlah_pekerja }} Orang</td>
                                <td class="px-6 py-4">{{ $data->jam_kerja }} Jam</td>

                                <!-- Safety Avatar -->
                                <td class="px-6 py-4">
                                    <div class="flex -space-x-2">
                                        @foreach ($data->users->take(3) as $user)
                                            <div
                                                class="w-8 h-8 rounded-full border-2 border-white bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endforeach

                                        @if ($data->users->count() > 3)
                                            <div
                                                class="w-8 h-8 rounded-full border-2 border-white bg-gray-100 text-gray-700 flex items-center justify-center text-xs font-semibold">
                                                +{{ $data->users->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                        {{ $data->status_validasi === 'valid' ? 'bg-green-50 text-green-700' :
                                           ($data->status_validasi === 'revisi' ? 'bg-yellow-50 text-yellow-700' :
                                           ($data->status_validasi === 'menunggu validasi' ? 'bg-blue-50 text-blue-700' : 'bg-red-50 text-red-700')) }}">
                                        {{ ucfirst($data->status_validasi) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">{{ $data->tanggal }}</td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('daily-report.show', $data->id) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 border rounded-lg text-indigo-600 hover:bg-indigo-600 hover:text-white transition">
                                        <i class="ri-eye-line"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                                    Belum ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            

            <!-- Footer -->
            <div class="text-xs text-gray-400 mt-4 text-center">
                {{ $datas->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
