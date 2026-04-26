<x-layouts.app title="Export Laporan">
<div 
    x-data="{ tab: 'weekly', mode: 'month' }"
    class="bg-white w-full rounded-lg shadow-lg p-8"
>

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Export Laporan Safety Patrol</h1>
        <p class="text-slate-500 mt-1">
            Pilih jenis laporan dan periode yang ingin diexport
        </p>
    </div>

    <!-- TAB SWITCH -->
    <div class="flex bg-slate-100 rounded-xl p-1 mb-8">
        <button
            @click="tab = 'weekly'"
            :class="tab === 'weekly' ? 'bg-white shadow text-blue-600' : 'text-slate-500'"
            class="flex-1 py-2 rounded-lg font-semibold transition"
        >
            Mingguan
        </button>
        <button
            @click="tab = 'monthly'"
            :class="tab === 'monthly' ? 'bg-white shadow text-blue-600' : 'text-slate-500'"
            class="flex-1 py-2 rounded-lg font-semibold transition"
        >
            Bulanan
        </button>
    </div>

    <!-- ================= WEEKLY ================= -->
    <form 
        x-show="tab === 'weekly'" 
        x-transition
        method="GET"
        action="{{ route('laporan.export') }}"
        class="space-y-6"
    >
        <input type="hidden" name="type" value="weekly">

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Tanggal Mulai Mingguan
            </label>
            <input 
                type="date" 
                name="tanggal_mulai"
                required
                class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
            >
        </div>

        <button class="w-full bg-blue-600 text-white py-3 rounded-lg">
            Export Laporan Mingguan
        </button>
    </form>

    <!-- ================= MONTHLY ================= -->
    <form 
        x-show="tab === 'monthly'" 
        x-transition
        method="GET"
        action="{{ route('laporan.export') }}"
        class="space-y-6"
    >
        <input type="hidden" name="type" value="monthly">

        <!-- MODE PILIHAN -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Pilih Metode
            </label>

            <div class="flex gap-4">
                <label class="flex items-center gap-2">
                    <input type="radio" value="month" name="mode" x-model="mode">
                    <span>Pilih Bulan</span>
                </label>

                <label class="flex items-center gap-2">
                    <input type="radio" value="custom" name="mode" x-model="mode">
                    <span>Custom Tanggal</span>
                </label>
            </div>
        </div>

        <!-- MODE: BULAN -->
        <div x-show="mode === 'month'" x-transition>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Pilih Bulan
            </label>

            <div class="grid grid-cols-2 gap-4">
                <select name="month"
                    class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Bulan --</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>

                <!-- optional: pilih tahun -->
                <select name="year"
                    class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                    @for ($y = now()->year; $y >= 2020; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <p class="text-xs text-slate-500 mt-1">
                Data akan diambil dari tanggal 1 sampai akhir bulan
            </p>
        </div>

        <!-- MODE: CUSTOM -->
        <div x-show="mode === 'custom'" x-transition class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tanggal Mulai
                </label>
                <input type="date" name="start_date"
                    class="w-full rounded-lg border-slate-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tanggal Akhir
                </label>
                <input type="date" name="end_date"
                    class="w-full rounded-lg border-slate-300">
            </div>
        </div>

        <button
            type="submit"
            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition"
        >
            Export Laporan Bulanan
        </button>
    </form>

</div>
</x-layouts.app>