<x-layouts.app title="Aktifitas Saya">
    <div class="min-h-screen bg-white rounded-lg shadow-md p-6">
        <div class="mx-auto">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Aktivitas Saya</h1>
    
            <!-- Filter & Search -->
            <div class="flex flex-wrap items-center justify-between mb-6">
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Semua</button>
                    <button class="px-4 py-2 bg-white text-gray-700 border rounded-lg text-sm hover:bg-gray-100">Peminjaman</button>
                    <button class="px-4 py-2 bg-white text-gray-700 border rounded-lg text-sm hover:bg-gray-100">Pengembalian</button>
                    <button class="px-4 py-2 bg-white text-gray-700 border rounded-lg text-sm hover:bg-gray-100">Stok</button>
                </div>
                <input type="text" placeholder="Cari aktivitas..." class="px-3 py-2 border rounded-lg text-sm w-64 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
    
            <!-- Timeline -->
            <div class="relative border-l border-gray-200 space-y-6">
                <!-- Item -->
                <div class="ml-6 relative">
                    <div class="absolute -left-3 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white">
                        <i class="ri-tools-line text-base"></i>
                    </div>
                    <div class="bg-white shadow-sm rounded-xl p-4 border hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-gray-800 font-medium">Peminjaman Alat</h3>
                            <span class="text-sm text-gray-500">12 Nov 2025, 09:24</span>
                        </div>
                        <p class="text-gray-600 text-sm">
                            Kamu meminjam <strong>2 Helm Safety</strong> dan <strong>1 APD</strong>.
                        </p>
                        <div class="mt-2">
                            <span class="inline-block px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Peminjaman</span>
                        </div>
                    </div>
                </div>
    
                <!-- Item -->
                <div class="ml-6 relative">
                    <div class="absolute -left-3 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white">
                        <i class="ri-checkbox-circle-line text-base"></i>
                    </div>
                    <div class="bg-white shadow-sm rounded-xl p-4 border hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-gray-800 font-medium">Pengembalian Alat</h3>
                            <span class="text-sm text-gray-500">13 Nov 2025, 15:02</span>
                        </div>
                        <p class="text-gray-600 text-sm">
                            Kamu mengembalikan <strong>1 Helm Safety</strong> dalam kondisi baik.
                        </p>
                        <div class="mt-2">
                            <span class="inline-block px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full">Pengembalian</span>
                        </div>
                    </div>
                </div>
    
                <!-- Item -->
                <div class="ml-6 relative">
                    <div class="absolute -left-3 w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center text-white">
                        <i class="ri-box-3-line text-base"></i>
                    </div>
                    <div class="bg-white shadow-sm rounded-xl p-4 border hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-gray-800 font-medium">Update Stok</h3>
                            <span class="text-sm text-gray-500">14 Nov 2025, 10:17</span>
                        </div>
                        <p class="text-gray-600 text-sm">
                            Stok <strong>APD</strong> ditambah sebanyak <strong>5 unit</strong>.
                        </p>
                        <div class="mt-2">
                            <span class="inline-block px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Stok</span>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Empty State (kalau tidak ada data) -->
            {{-- 
            <div class="flex flex-col items-center justify-center mt-12 text-center text-gray-500">
                <i class="ri-time-line text-5xl mb-3"></i>
                <p>Tidak ada aktivitas terbaru.</p>
            </div>
            --}}
        </div>
    </div>
</x-layouts.app>



