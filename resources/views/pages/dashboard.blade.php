<x-layouts.app title="Dashboard">
    <div class="min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Dashboard Keselamatan</h1>
                <p class="text-gray-500 text-sm">Ringkasan aktivitas dan kondisi terbaru proyek keselamatan kerja.</p>
            </div>
    
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Safety Patrol -->
                <div class="bg-white rounded-xl p-5 shadow-sm border hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Laporan Safety Patrol</p>
                            <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ $patrolWeekNow }}</h2>
                        </div>
                        <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full">
                            <i class="ri-shield-check-line text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-gray-500">
                        <span class="@if ($patrolPercent < 0)
                            text-red-600
                            @else
                            text-green-600
                        @endif font-medium">{{ $patrolPercent }}%</span> dari minggu lalu
                    </div>
                </div>
    
                <!-- Safety Briefing -->
                <div class="bg-white rounded-xl p-5 shadow-sm border hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Safety Briefing</p>
                            <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ $breafingWeekNow }}</h2>
                        </div>
                        <div class="w-10 h-10 flex items-center justify-center bg-green-100 text-green-600 rounded-full">
                            <i class="ri-group-line text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-gray-500">
                        <span class="text-green-600 font-medium">{{ $breafingPercent }}%</span> dari minggu lalu
                    </div>
                </div>
    
                <!-- APD -->
                <div class="bg-white rounded-xl p-5 shadow-sm border hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Alat & APD Aktif</p>
                            <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ $toolsStock }}</h2>
                        </div>
                        <div class="w-10 h-10 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full">
                            <i class="ri-tools-line text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-gray-500 ">
                        <span class="text-red-500 font-medium hidden">-3%</span> Dari total stock APD
                    </div>
                </div>
    
                <!-- Inspeksi Selesai -->
                <div class="bg-white rounded-xl p-5 shadow-sm border hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Project Selesai</p>
                            <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($projectPercentage, 0) }}%</h2>
                        </div>
                        <div class="w-10 h-10 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-full">
                            <i class="ri-pie-chart-line text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-gray-500">
                        Dari total proyek aktif
                    </div>
                </div>
            </div>
    
            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Charts & Reports -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Grafik -->
                    <div class="bg-white rounded-xl p-5 shadow-sm border">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-800">Unsafe Action & Unsafe Condition</h3>
                        </div>
                        <div class="h-[400px] flex items-center justify-center text-gray-400 text-sm">
                            <canvas id='myChart'></canvas>
                        </div>
                    </div>
    
                    <!-- Recent Activities -->
                    <div class="bg-white rounded-xl p-5 shadow-sm border">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                        <ul class="divide-y">
                            <li class="py-3 flex items-start justify-between">
                                <div>
                                    <p class="text-gray-700 font-medium">Patroli Proyek A</p>
                                    <p class="text-gray-500 text-sm">Menemukan 2 potensi bahaya, 1 ditindaklanjuti.</p>
                                </div>
                                <span class="text-xs text-gray-400">2 jam lalu</span>
                            </li>
                            <li class="py-3 flex items-start justify-between">
                                <div>
                                    <p class="text-gray-700 font-medium">Safety Briefing Tim B</p>
                                    <p class="text-gray-500 text-sm">Topik: APD dan keselamatan area tinggi.</p>
                                </div>
                                <span class="text-xs text-gray-400">5 jam lalu</span>
                            </li>
                            <li class="py-3 flex items-start justify-between">
                                <div>
                                    <p class="text-gray-700 font-medium">Pengembalian APD</p>
                                    <p class="text-gray-500 text-sm">Helm dan rompi dikembalikan dalam kondisi baik.</p>
                                </div>
                                <span class="text-xs text-gray-400">Kemarin</span>
                            </li>
                        </ul>
                    </div>
                </div>
    
                <!-- Right: Summary -->
                <div class="space-y-8">
                    <!-- Progress Section -->
                    <div class="bg-white rounded-xl p-5 shadow-sm border">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Status APD</h3>
                        <div class="space-y-4">
                            @foreach ($tools as $tool)
                                <div>
                                <p class="text-sm text-gray-600 mb-1">{{ $tool->name }}</p>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="@if ($tool['stock'] > $tool['stock_minimum'] * 0.10)
                                        bg-green-600
                                    @else
                                        bg-red-600
                                    @endif h-2 rounded-full max-w-full" style="width: {{ ($tool->stock / $tool->stock_minimum) * 100 }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $tool->stock }}/{{ $tool->stock_minimum }} stock tersisa</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
    
                    <!-- Reminder Section -->
                    <div class="hidden bg-gradient-to-r from-blue-600 to-blue-400 text-white rounded-xl p-5 shadow-md">
                        <h3 class="text-lg font-semibold mb-2">Ingatkan Tim!</h3>
                        <p class="text-sm opacity-90 mb-4">
                            Beberapa tim belum mengisi laporan briefing harian. Kirimkan pengingat sekarang.
                        </p>
                        <button class="px-4 py-2 bg-white text-blue-600 rounded-lg text-sm font-medium hover:bg-gray-100 transition">
                            Kirim Pengingat
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    new Chart(document.getElementById('myChart'), {
    type: 'bar', // 🔥 ini kuncinya (diagram batang)
    data: {
        labels: @json($dates),
        datasets: [
            {
                label: 'Unsafe Action',
                data: @json($ua),
                backgroundColor: 'rgba(255, 99, 132, 0.7)'
            },
            {
                label: 'Unsafe Condition',
                data: @json($uc),
                backgroundColor: 'rgba(255, 159, 64, 0.7)'
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0 // biar angka bulat
                }
            }
        }
    }
});
</script>
</x-layouts.app>