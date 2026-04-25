<x-layouts.app title="Daftar User">
    <div x-data="{ search: '', sort: 'latest' }" class="p-4 sm:p-0">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">Daftar User</h1>
                    <p class="text-gray-500 text-sm">Data seluruh user terdaftar.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('user.create') }}"
                        class="flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                        <i class="ri-user-add-line"></i> Tambah User
                    </a>

                    <form action="{{ route('user.index') }}" method="get" class="relative w-full sm:w-64">
                        <input name="search" value="{{ request()->search }}" type="text" placeholder="Cari user..." x-model="search"
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-violet-200 focus:border-violet-400 text-sm">
                        <i class="ri-search-line absolute left-3 top-2.5 text-gray-400"></i>
                    </form>
                </div>
            </div>

            <!-- Sort -->
            <div class="flex justify-end mb-4">
                <select onchange="location.href=this.value"
                    class="w-full sm:w-auto px-4 py-2 rounded-xl border border-gray-300 text-gray-600 text-sm focus:border-violet-300">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'terbaru']) }}" @if(request()->sort == 'terbaru') selected @endif>Terbaru</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'terlama']) }}" @if(request()->sort == 'terlama') selected @endif>Terlama</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}" @if(request()->sort == 'name_asc') selected @endif>Nama A-Z</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_desc']) }}" @if(request()->sort == 'name_desc') selected @endif>Nama Z-A</option>
                </select>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-[700px] w-full text-left text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-3 px-4">User</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700">
                        @foreach ($users as $user)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-4 py-3 flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-violet-600 text-white flex items-center justify-center font-medium shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="leading-tight">
                                        <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->role ?? '—' }}</div>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-gray-600 break-all">
                                    {{ $user->email }}
                                </td>

                                <td class="px-4 py-3 capitalize">
                                    <span
                                        class="px-3 py-1 text-xs rounded-full
                                        {{ $user->role === 'admin'
                                            ? 'bg-blue-100 text-blue-700'
                                            : 'bg-gray-100 text-gray-700' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-right relative" x-data="{ open: false }">
                                    <a href="{{ route('user.show', $user->id) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 border rounded-lg text-indigo-600 hover:bg-indigo-600 hover:text-white transition">
                                        <i class="ri-eye-line"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center sm:text-right text-xs sm:text-sm text-gray-500 mt-4">
                Diperbarui terakhir: {{ now()->format('d M Y, H:i') }}
            </div>

        </div>
    </div>
</x-layouts.app>
