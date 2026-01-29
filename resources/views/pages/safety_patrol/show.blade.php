<x-layouts.app title="Detail Project">
    <div class="min-h-screen">
        <div class="mx-auto" x-data="{ openModalUpdate: false }">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">Detail Project Safety Patrol</h1>
                    <p class="text-sm text-gray-500 mt-1">Informasi lengkap mengenai project safety patrol dan progres
                        lapangan.</p>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <button @click="openModalUpdate = true;"
                        class="flex items-center gap-2 bg-blue-700 border border-blue-300 text-white hover:bg-blue-600 px-4 py-2 rounded-lg text-sm shadow-sm transition w-full sm:w-auto justify-center">
                        <i class="ri-refresh-line"></i> Update
                    </button>

                    <a href="{{ route('project.index') }}"
                        class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 px-4 py-2 rounded-lg text-sm shadow-sm transition w-full sm:w-auto justify-center">
                        <i class="ri-arrow-left-line"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Project Card -->
            <div class="bg-white border rounded-lg shadow-sm p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">

                    <div class="w-full">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">{{ $project->nama }}</h2>

                        <!-- Info bar -->
                        <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm text-gray-600 mb-2">
                            <span class="flex items-center gap-1"><i class="ri-map-pin-line"></i>
                                {{ $project->lokasi }}</span>
                            <span class="flex items-center gap-1"><i class="ri-calendar-line"></i>
                                Mulai: {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1"><i class="ri-calendar-check-line"></i>
                                Target: {{ \Carbon\Carbon::parse($project->tanggal_selesai)->format('d M Y') }}
                            </span>
                        </div>

                        <p class="text-gray-500 text-sm max-w-xl leading-relaxed">
                            {{ $project->deskripsi }}
                        </p>
                    </div>

                    <div class="mt-2 sm:mt-0">
                        @if ($project->status === 'Berjalan')
                            <span
                                class="inline-flex items-center gap-1.5 text-sm font-medium bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                                <i class="ri-refresh-line animate-spin"></i> {{ $project->status }}
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 text-sm font-medium bg-green-50 text-green-700 px-3 py-1 rounded-full">
                                <i class="ri-check-line"></i> {{ $project->status }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Progress -->
                <div class="mt-6">
                    <div class="flex justify-between items-center text-xs sm:text-sm text-gray-600 mb-1">
                        <span>Progress</span>
                        <span>{{ $project->status === 'Selesai' ? '100' : ($project->dailySafetyPatrol->count() / $period) * 100 }}%</span>
                    </div>

                    <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                        <div class="h-2 bg-indigo-600 rounded-full"
                            style="width: {{ $project->status === 'Selesai' ? '100' : ($project->dailySafetyPatrol->count() / $period) * 100 }}%">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                <!-- Laporan -->
                <div class="bg-white border rounded-lg shadow-sm p-6 flex flex-col">
                    <div class="flex justify-between items-center mb-4 flex-wrap gap-3">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="ri-file-list-3-line text-blue-500"></i> Laporan Safety Patrol
                        </h3>

                        <a class="flex items-center gap-2 bg-blue-700 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs font-medium shadow-sm transition"
                            href="{{ route('daily-report.create') }}">
                            <i class="ri-add-line"></i> Tambah Laporan
                        </a>
                    </div>

                    <div class="space-y-4 flex-1">
                        @if ($laporans->count())
                            @foreach ($laporans as $key => $laporan)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-medium text-gray-800 text-sm sm:text-base">
                                            Laporan Harian {{ $project->dailySafetyPatrol->count() - $key }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}
                                        </span>
                                    </div>

                                    <p class="text-xs sm:text-sm text-gray-600 mb-2">{{ $laporan->deskripsi }}</p>

                                    <div class="flex flex-wrap items-center gap-2">

                                        @php
                                            $badgeClass = match ($laporan->status_validasi) {
                                                'menunggu validasi' => 'bg-blue-50 text-blue-700',
                                                'divalidasi' => 'bg-green-50 text-green-700',
                                                'revisi' => 'bg-orange-50 text-orange-700',
                                                default => 'bg-red-50 text-red-700',
                                            };
                                        @endphp

                                        <span
                                            class="text-xs font-medium px-2 py-1 rounded-full capitalize {{ $badgeClass }}">
                                            {{ $laporan->status_validasi }}
                                        </span>

                                        <a href="{{ route('daily-report.show', $laporan->id) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <span class="text-gray-500 text-center block py-6">Belum ada laporan.</span>
                        @endif
                    </div>
                </div>

                <!-- Kalender -->
                <div class="bg-white p-5 rounded-lg shadow-sm border">
                    <h3 class="text-lg font-semibold flex items-center gap-2 mb-4">
                        <i class="ri-calendar-event-line text-blue-500 text-xl"></i>
                        Kalender Laporan
                    </h3>

                    <!-- Header Hari -->
                    <div class="grid grid-cols-7 text-center text-[10px] sm:text-sm font-semibold text-gray-500 mb-3">
                        <div>Sen</div>
                        <div>Sel</div>
                        <div>Rab</div>
                        <div>Kam</div>
                        <div>Jum</div>
                        <div>Sab</div>
                        <div>Min</div>
                    </div>

                    <!-- Kalender grid -->
                    <div class="grid grid-cols-7 gap-1 sm:gap-2 text-center text-xs sm:text-sm">
                        @php
                            $start = $startMonth->copy();
                            $end = $endMonth->copy();
                            $firstDayPos = $start->dayOfWeekIso;
                        @endphp
                        @for ($i = 1; $i < $firstDayPos; $i++)
                            <div></div>
                        @endfor

                        @for ($day = 1; $day <= $end->day; $day++)
                            @php
                                $current = $startMonth->copy()->day($day);
                                $dateStr = $current->format('Y-m-d');
                                $hasReport = in_array($dateStr, $reports);
                                $isWeekend = $current->isSunday();
                            @endphp

                            <div
                                class="
                            h-10 sm:h-12 flex items-center justify-center rounded-lg border text-xs sm:text-sm
                            transition
                            {{ $hasReport
                                ? 'bg-green-100 border-green-300 text-green-700 font-semibold'
                                : ($isWeekend
                                    ? 'bg-gray-100 text-gray-400 border-gray-200'
                                    : 'bg-white text-gray-800 border-gray-200 hover:bg-gray-100') }}
                        ">
                                {{ $day }}
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Download & Ekspor -->
            <div class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="ri-download-cloud-2-line text-2xl text-blue-500 mr-2"></i>
                    Export & Download
                </h3>

                <p class="text-gray-500 text-sm mb-4">Pilih format laporan yang ingin kamu unduh.</p>

                <div class="flex flex-col sm:flex-row gap-3">

                    <button
                        class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl bg-white border 
                       hover:shadow-lg hover:-translate-y-1 transition-all">
                        <i class="ri-file-pdf-line text-red-500 text-xl"></i> PDF
                    </button>

                    <a href="{{ route('laporan.export') }}"
                        class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl bg-white border 
                       hover:shadow-lg hover:-translate-y-1 transition-all">
                        <i class="ri-file-excel-line text-green-500 text-xl"></i> Excel
                </a>

                    <button
                        class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl bg-white border 
                       hover:shadow-lg hover:-translate-y-1 transition-all">
                        <i class="ri-file-line text-gray-700 text-xl"></i> CSV
                    </button>

                </div>
            </div>

            <!-- Delete Box -->
            <div x-data="{ confirmDelete: false }" class="p-6 mt-6 bg-white rounded-lg shadow-sm border border-gray-100">

                <div class="flex items-start sm:items-center justify-between gap-4 flex-wrap">
                    <div class="min-w-[200px]">
                        <h2 class="text-lg font-semibold text-gray-800">Hapus Project</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Menghapus project juga akan menghapus semua laporan terkait.
                        </p>
                    </div>

                    <div class="p-2 bg-red-50 rounded-lg self-start sm:self-center">
                        <i class="ri-delete-bin-6-line text-red-500 text-xl"></i>
                    </div>
                </div>

                <button @click="confirmDelete = true"
                    class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-700 text-white rounded-lg hover:bg-red-600 transition font-medium">
                    <i class="ri-delete-bin-line text-lg"></i> Hapus
                </button>

                <!-- Modal Konfirmasi -->
                <div x-show="confirmDelete" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50"
                    x-transition.opacity>

                    <div class="bg-white w-full max-w-sm rounded-lg p-6 shadow-xl" x-transition.scale>

                        <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Penghapusan</h3>
                        <p class="text-sm text-gray-500 mt-2">
                            Apakah Anda yakin? Tindakan ini tidak dapat dibatalkan.
                        </p>

                        <div class="flex items-center justify-end gap-3 mt-6">

                            <button @click="confirmDelete = false"
                                class="px-4 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-100 transition">
                                Batal
                            </button>

                            <form method="POST" action="{{ route('project.destroy', $project->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 rounded-lg bg-red-500 text-sm text-white hover:bg-red-600 transition">
                                    Ya, Hapus
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <!-- MODAL UPDATE -->
            <div x-show="openModalUpdate" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50"
                x-transition.opacity>

                <div x-show="openModalUpdate" x-transition.scale
                    class="bg-white rounded-lg w-full max-w-4xl p-6 shadow-xl border border-gray-200 overflow-y-scroll max-h-[90vh] sm:max-h-auto sm:overflow-y-hidden">

                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Update Data</h2>

                        <button @click="openModalUpdate = false" class="text-gray-400 hover:text-gray-600">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>

                    <form action="{{ route('project.update', $project->id) }}" method="post"
                        enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="nama">
                                    Judul Project <span class="text-red-500">*</span>
                                </label>
                                <input name="nama" id="nama" type="text" value="{{ $project->nama }}"
                                    class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 rounded-lg shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="lokasi">
                                    Lokasi <span class="text-red-500">*</span>
                                </label>
                                <input name="lokasi" id="lokasi" type="text" value="{{ $project->lokasi }}"
                                    class="w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 rounded-lg shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="status">
                                    Status
                                </label>
                                <select name="status" id="status"
                                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 shadow-sm">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" @selected($project->status === $status)>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="deskripsi">
                                Deskripsi
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="4"
                                class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 shadow-sm resize-none">{{ $project->deskripsi }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input name="tanggal_mulai" type="date" value="{{ $project->tanggal_mulai }}"
                                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Target Selesai</label>
                                <input name="tanggal_selesai" type="date" value="{{ $project->tanggal_selesai }}"
                                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 shadow-sm">
                            </div>
                        </div>

                        <div class="flex justify-end gap-2.5">
                            <button @click="openModalUpdate = false" type="button"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:border-indigo-400 hover:text-indigo-600">
                                Batal
                            </button>

                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md">
                                Simpan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</x-layouts.app>
