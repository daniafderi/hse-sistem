<x-layouts.app title="History">
    <div class="max-w-4xl mx-auto p-4 py-10" x-data="{ filter: 'all', user: 'Dani' }">


        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Riwayat Aktivitas</h1>
            <p class="text-gray-600 text-sm">Menampilkan aktivitas milik <span class="font-semibold"
                    x-text="user"></span>.</p>
        </div>


        <!-- Filter -->
        <div class="flex gap-3 mb-6">
            <button class="px-4 py-2 bg-white shadow rounded-xl border hover:bg-gray-50" @click="filter='all'">
                <i class="ri-time-line"></i> Semua
            </button>
            <button class="px-4 py-2 bg-white shadow rounded-xl border hover:bg-gray-50" @click="filter='report'">
                <i class="ri-file-list-3-line"></i> Laporan
            </button>
            <button class="px-4 py-2 bg-white shadow rounded-xl border hover:bg-gray-50" @click="filter='apd'">
                <i class="ri-shield-check-line"></i> APD
            </button>
            <button class="px-4 py-2 bg-white shadow rounded-xl border hover:bg-gray-50" @click="filter='borrow'">
                <i class="ri-key-2-line"></i> Peminjaman
            </button>
        </div>


        <!-- History List -->
        <div class="space-y-4">


            <!-- Daily Safety Report -->
            <template x-if="filter === 'all' || filter === 'report'">
                <div
                    class="bg-white shadow rounded-2xl p-4 border border-gray-200 flex justify-between items-center hover:shadow-lg transition">
                    <div>
                        <h2 class="font-semibold">Laporan Harian Safety Dibuat</h2>
                        <p class="text-sm text-gray-600">Oleh: <span class="font-medium" x-text="user"></span> • 27 Nov
                            2025 • 08:10</p>
                    </div>
                    <i class="ri-file-list-3-line text-2xl text-blue-600"></i>
                </div>
            </template>


            <!-- Safety Briefing -->
            <template x-if="filter === 'all' || filter === 'report'">
                <div
                    class="bg-white shadow rounded-2xl p-4 border border-gray-200 flex justify-between items-center hover:shadow-lg transition">
                    <div>
                        <h2 class="font-semibold">Safety Briefing Dikonfirmasi</h2>
                        <p class="text-sm text-gray-600">Oleh: <span class="font-medium" x-text="user"></span> • 27 Nov
                            2025 • 07:30</p>
                    </div>
                    <i class="ri-alert-line text-2xl text-orange-500"></i>
                </div>
            </template>


            <!-- APD Management -->
            <template x-if="filter === 'all' || filter === 'apd'">
                <div
                    class="bg-white shadow rounded-2xl p-4 border border-gray-200 flex justify-between items-center hover:shadow-lg transition">
                    <div>
                        <h2 class="font-semibold">APD Diupdate</h2>
                        <p class="text-sm text-gray-600">Dikerjakan oleh: <span class="font-medium"
                                x-text="user"></span> • 26 Nov 2025 • 13:15</p>
                    </div>
                    <i class="ri-shield-check-line text-2xl text-green-600"></i>
                </div>
        </div>
</x-layouts.app>
