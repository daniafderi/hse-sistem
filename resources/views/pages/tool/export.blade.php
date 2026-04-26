<x-layouts.app title="Export Laporan APD">
    <div 
    x-data="{ tab: 'weekly' }"
    class="bg-white w-full rounded-lg shadow-lg p-8"
>
    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Export Laporan APD</h1>
        <p class="text-slate-500 mt-1">
            Pilih tanggal mulai laporan (otomatis ambil 6 hari kedepan)
        </p>
    </div>

    <!-- TAB SWITCH -->
    <div class="hidden bg-slate-100 rounded-xl p-1 mb-8">
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

    <!-- ================= WEEKLY EXPORT ================= -->
    <form 
        x-show="tab === 'weekly'" 
        x-transition
        method="GET"
        action="{{ route('tool.export') }}"
        class="space-y-6"
    >
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
            <p class="text-xs text-slate-500 mt-1">
                Laporan akan diambil selama 7 hari dari tanggal ini
            </p>
        </div>

        <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition"
        >
            Export Laporan Mingguan
        </button>
    </form>

    <!-- ================= MONTHLY EXPORT ================= -->
    <form 
        x-show="tab === 'monthly'" 
        x-transition
        method="GET"
        action="{{ route('laporan.export') }}"
        class="space-y-6"
    >
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Tanggal Mulai
            </label>
            <input 
                type="date" 
                name="start_date"
                required
                class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Pilih Bulan
            </label>
            <select 
                name="month"
                required
                class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">-- Pilih Bulan --</option>
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