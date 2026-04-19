<x-layouts.app title="Detail User">
    <div class="space-y-6">

        <!-- Profile Header -->
        <div class="bg-white p-6 rounded-2xl shadow-sm flex gap-6 items-center">
            <img src="https://i.pravatar.cc/120" class="w-24 h-24 rounded-full" />

            <div class="flex-1">
                <h2 class="text-2xl font-semibold">{{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->role }}</p>

                <div class="mt-2 flex gap-4 text-sm text-gray-500">
                    <span>Email: {{ $user->email }}</span>
                    <span>ID: {{ $user->id }}</span>
                    <span class="text-green-600 font-medium">Aktif</span>
                </div>
            </div>

            <button class="px-4 py-2 bg-blue-600 text-white rounded-xl">Edit User</button>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-3 gap-4">

            <div class="bg-white p-4 rounded-2xl shadow-sm">
                <p class="text-gray-500">Total Project</p>
                <h3 class="text-3xl font-semibold mt-2">{{ $totalProject }}</h3>
                <p class="text-sm text-green-600 mt-1">+1 minggu ini</p>
            </div>

            <div class="bg-white p-4 rounded-2xl shadow-sm">
                <p class="text-gray-500">Total Laporan</p>
                <h3 class="text-3xl font-semibold mt-2">{{ $totalReport }}</h3>
                <p class="text-sm text-blue-600 mt-1">+12% dari minggu lalu</p>
            </div>

            <div class="bg-white p-4 rounded-2xl shadow-sm">
                <p class="text-gray-500">Safety Briefing</p>
                <h3 class="text-3xl font-semibold mt-2">{{ $totalBriefing }}</h3>
                <p class="text-sm text-gray-500 mt-1">berdasarkan 30 hari terakhir</p>
            </div>

        </div>

        <!-- Kontribusi -->
        <div class="bg-white p-6 rounded-2xl shadow-sm hidden">

            <h3 class="text-xl font-semibold mb-4">Kontribusi Project</h3>

            <div class="grid grid-cols-2 gap-4">

                <div class="border p-4 rounded-xl hover:bg-gray-50 transition">
                    <p class="text-gray-500 text-sm">Project</p>
                    <h4 class="text-lg font-semibold mt-1">Patrol Area A</h4>
                    <p class="text-sm text-gray-400">Dibuat: 12 Januari 2025</p>
                </div>

                <div class="border p-4 rounded-xl hover:bg-gray-50 transition">
                    <p class="text-gray-500 text-sm">Project</p>
                    <h4 class="text-lg font-semibold mt-1">Audit Gudang</h4>
                    <p class="text-sm text-gray-400">Dibuat: 4 Februari 2025</p>
                </div>

            </div>
        </div>

        <!-- Riwayat Laporan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm">

            <h3 class="text-xl font-semibold mb-4">Riwayat Laporan</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="border-b text-gray-600">
                            <th class="py-2">Tanggal</th>
                            <th class="py-2">Project</th>
                            <th class="py-2">Jenis Laporan</th>
                            <th class="py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">

                        @if ($user->dailyReport->count() > 0)
                            @foreach ($user->dailyReport as $report)
                                <tr class="border-b">
                                    <td class="py-3">{{ $report->created_at }}</td>
                                    <td>{{ $report->project->nama }}</td>
                                    <td>Safety Patrol</td>
                                    <td>
                                        <span class="@if ($report->status_validasi === 'valid')
                                            text-green-600
                                        @elseif($report->status_validasi === 'menunggu validasi')
                                            text-blue-600
                                            @elseif ($report->status_validasi === 'revisi')
                                            text-orange-600
                                            @elseif ($report->status_validasi === 'ditolak')
                                            text-red-600
                                        @endif font-medium capitalize">{{ $report->status_validasi }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>

        </div>

    </div>

</x-layouts.app>
