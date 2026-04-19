<x-layouts.app title="Laporan Detail">
<div class="min-h-screen px-4 sm:px-6 lg:px-0"
     x-data="{ preview: false, imageSrc: '', openModal: false }">

<div class="max-w-6xl mx-auto">

<!-- ================= HEADER ================= -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">
            Detail Laporan Safety Patrol
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $dailyReport->project->nama }} - 
            <span class="inline-block mt-1 sm:mt-0 text-xs px-3 py-1 rounded-full font-medium border
            @if ($dailyReport->status_validasi === 'menunggu validasi') bg-blue-100 text-blue-700 border-blue-300
            @elseif ($dailyReport->status_validasi === 'valid') bg-green-100 text-green-700 border-green-300
            @elseif ($dailyReport->status_validasi === 'revisi') bg-yellow-100 text-yellow-700 border-yellow-300
            @else bg-red-100 text-red-700 border-red-300 @endif">
                {{ ucfirst($dailyReport->status_validasi) }}
            </span>
        </p>
    </div>

    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        <button @click="openModal = true"
            class="w-full sm:w-auto px-4 py-2 bg-blue-700 hover:bg-blue-600 text-white rounded-lg shadow text-sm">
            <i class="ri-check-line"></i> Validasi
        </button>

        <a href="{{ route('daily-report.index') }}"
           class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 px-4 py-2 rounded-lg text-sm shadow-sm transition w-full sm:w-auto justify-center">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
    </div>
</div>

<!-- ================= INFORMASI UMUM ================= -->
<div class="bg-white rounded-xl shadow-sm border p-4 sm:p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <ul class="text-sm text-gray-600 space-y-2">
            <li><span class="font-medium w-32 inline-block">Tanggal</span> : {{ $dailyReport->tanggal }}</li>
            <li><span class="font-medium w-32 inline-block">Lokasi</span> : {{ $dailyReport->project->lokasi }}</li>
            <li><span class="font-medium w-32 inline-block">Jam Kerja</span> : {{ $dailyReport->jam_kerja }} Jam</li>
            <li><span class="font-medium w-32 inline-block">Pekerja</span> : {{ $dailyReport->jumlah_pekerja }} Orang</li>
            <li><span class="font-medium w-32 inline-block">Permit</span> : {{ $dailyReport->permit }}</li>
        </ul>

        <div>
            <h3 class="font-semibold mb-2 text-gray-700">Petugas Patrol</h3>
            <div class="flex flex-wrap gap-2">
                @foreach ($dailyReport->users as $user)
                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-sm border">
                        <i class="ri-user-line"></i> {{ $user->name }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- ================= VALIDASI ================= -->
<div class="bg-white rounded-xl shadow-sm border p-4 sm:p-6 mb-6 overflow-x-auto">
    <h2 class="font-semibold text-gray-800 mb-3">Catatan Validasi</h2>

    <table class="min-w-[600px] w-full text-sm text-gray-700">
        <thead class="border-b bg-gray-50">
            <tr>
                <th class="text-left py-2">Validator</th>
                <th class="text-left py-2">Status</th>
                <th class="text-left py-2">Komentar</th>
                <th class="text-left py-2">Tanggal</th>
            </tr>
        </thead>
        <tbody class="divide-y">
        @forelse ($dailyReport->validations as $val)
            <tr>
                <td class="py-2">{{ $val->validator->name }}</td>
                <td class="py-2">
                    <span class="px-2 py-1 rounded-full text-xs
                    @if($val->status=='valid') bg-green-100 text-green-700
                    @elseif($val->status=='revisi') bg-yellow-100 text-yellow-700
                    @else bg-red-100 text-red-700 @endif">
                        {{ ucfirst($val->status) }}
                    </span>
                </td>
                <td class="py-2">{{ $val->komentar ?? '-' }}</td>
                <td class="py-2">{{ $val->created_at->format('d M Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="py-4 text-center text-gray-500">
                    Belum ada validasi
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- ================= UNSAFE ACTION ================= -->
<div class="bg-white rounded-xl shadow-sm border p-4 sm:p-6 mb-6">
    <h2 class="font-semibold mb-4 text-gray-800">Unsafe Action</h2>

    @foreach ($dailyReport->images->where('label','ua') as $ua)
    <div class="flex flex-col md:flex-row gap-4 mb-4 bg-slate-50 p-4 rounded-lg border">
        <div class="flex-1 text-sm">
            <b class="mb-1">Deskripsi : </b>
            <p class="text-sm text-gray-700">
                {{ $ua->text ?? 'Tidak ada deskripsi' }}
            </p>
            <b class="mb-1">Tindakan Perbaikan :</b>
            <p class="text-sm text-gray-700">
                {{ $ua->tindakan_perbaikan ?? 'Belum ada tindakan perbaikan' }}
            </p>
        </div>
        <div class="w-full md:w-48">
            @if($ua->image_url)
            <img src="{{ asset('storage/'.$ua->image_url) }}"
                 @click="imageSrc='{{ asset('storage/'.$ua->image_url) }}';preview=true"
                 class="rounded-lg h-32 w-full object-cover cursor-pointer">
            @else
            <div class="h-32 flex items-center justify-center text-gray-400 bg-gray-100 rounded">
                Tidak ada foto
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

<!-- ================= UNSAFE CONDITION ================= -->
<div class="bg-white rounded-xl shadow-sm border p-4 sm:p-6 mb-6">
    <h2 class="font-semibold mb-4 text-gray-800">Unsafe Condition</h2>

    @foreach ($dailyReport->images->where('label','uc') as $uc)
    <div class="flex flex-col md:flex-row gap-4 mb-4 bg-slate-50 p-4 rounded-lg border">
        <div class="flex-1 text-sm">
            <b class="mb-1">Deskripsi : </b>
            <p class="text-sm text-gray-700">
                {{ $uc->text ?? 'Tidak ada deskripsi' }}
            </p>
            <b class="mb-1">Tindakan Perbaikan :</b>
            <p class="text-sm text-gray-700">
                {{ $uc->tindakan_perbaikan ?? 'Belum ada tindakan perbaikan' }}
            </p>
        </div>
        <div class="w-full md:w-48">
            @if($uc->image_url)
            <img src="{{ asset('storage/'.$uc->image_url) }}"
                 @click="imageSrc='{{ asset('storage/'.$uc->image_url) }}';preview=true"
                 class="rounded-lg h-32 w-full object-cover cursor-pointer">
            @else
            <div class="h-32 flex items-center justify-center text-gray-400 bg-gray-100 rounded">
                Tidak ada foto
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

<!-- ================= TEMUAN TAMBAHAN ================= -->
<div class="bg-white rounded-xl shadow-sm border p-4 sm:p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i class="ri-alert-line text-amber-500"></i> Temuan Tambahan
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Nearmiss -->
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <h3 class="font-medium text-gray-800 mb-2 flex items-center gap-2">
                <i class="ri-error-warning-line text-orange-600"></i> Nearmiss
            </h3>
            <p class="text-sm text-gray-700">
                {{ $dailyReport->nearmiss ?: 'Tidak ada nearmiss pada laporan ini.' }}
            </p>
        </div>

        <!-- Kecelakaan -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="font-medium text-gray-800 mb-2 flex items-center gap-2">
                <i class="ri-first-aid-kit-line text-red-600"></i> Kecelakaan
            </h3>
            <p class="text-sm text-gray-700">
                {{ $dailyReport->kecelakaan ?: 'Tidak ada kecelakaan pada laporan ini.' }}
            </p>
        </div>

        <!-- Reward -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <h3 class="font-medium text-gray-800 mb-2 flex items-center gap-2">
                <i class="ri-medal-line text-green-600"></i> Reward
            </h3>
            <p class="text-sm text-gray-700">
                {{ $dailyReport->reward ?: 'Tidak ada reward yang diberikan.' }}
            </p>
        </div>

        <!-- Punishment -->
        <div class="bg-rose-50 border border-rose-200 rounded-lg p-4">
            <h3 class="font-medium text-gray-800 mb-2 flex items-center gap-2">
                <i class="ri-flag-2-line text-rose-600"></i> Punishment
            </h3>
            <p class="text-sm text-gray-700">
                {{ $dailyReport->punishment ?: 'Tidak ada punishment yang diberikan.' }}
            </p>
        </div>
    </div>
</div>


<!-- ================= MODAL VALIDASI ================= -->
<div x-show="openModal" x-transition class="fixed inset-0 z-40 bg-black/40 flex items-center justify-center">
    <div class="bg-white w-full max-w-md mx-4 p-6 rounded-xl">
        <h3 class="font-semibold mb-4">Validasi Laporan</h3>

        <form method="POST" action="{{ route('daily-report.validate',$dailyReport->id) }}">
            @csrf
            <select name="status" class="w-full border rounded-lg mb-3 px-3 py-2">
                <option value="">Pilih Status</option>
                <option value="valid">Valid</option>
                <option value="revisi">Revisi</option>
                <option value="ditolak">Ditolak</option>
            </select>

            <textarea name="komentar" rows="3"
                class="w-full border rounded-lg mb-4 px-3 py-2"
                placeholder="Catatan"></textarea>

            <div class="flex justify-end gap-2">
                <button type="button" @click="openModal=false"
                    class="px-4 py-2 border rounded-lg">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= IMAGE PREVIEW ================= -->
<div x-show="preview" x-transition
     class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center">
    <img :src="imageSrc" class="max-h-[85vh] rounded-lg shadow-lg">
    <button @click="preview=false"
        class="absolute top-4 right-4 bg-white p-2 rounded-full">✕</button>
</div>

</div>
</div>
</x-layouts.app>
