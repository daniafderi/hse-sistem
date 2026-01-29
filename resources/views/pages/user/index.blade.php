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

                    <div class="relative w-full sm:w-64">
                        <input type="text" placeholder="Cari user..." x-model="search"
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-violet-200 focus:border-violet-400 text-sm">
                        <i class="ri-search-line absolute left-3 top-2.5 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Sort -->
            <div class="flex justify-end mb-4">
                <select x-model="sort"
                    class="w-full sm:w-auto px-4 py-2 rounded-xl border border-gray-300 text-gray-600 text-sm focus:border-violet-300">
                    <option value="latest">Urutkan: Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="name_asc">Nama A-Z</option>
                    <option value="name_desc">Nama Z-A</option>
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
                            <th class="py-3 px-4">Status</th>
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

                                <td class="px-4 py-3">
                                    <span
                                        class="text-xs px-3 py-1 rounded-full
                                        {{ $user->status
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-red-100 text-red-600' }}">
                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-right relative" x-data="{ open: false }">
                                    <button @click="open = !open"
                                        class="p-2 hover:bg-gray-200 rounded-lg">
                                        <i class="ri-more-2-line text-lg"></i>
                                    </button>

                                    <div x-show="open" @click.outside="open=false"
                                        class="absolute right-2 mt-2 w-40 bg-white shadow-lg border border-gray-200 rounded-xl z-20">
                                        <a class="block px-4 py-2 text-sm hover:bg-gray-50">Edit</a>
                                        <a class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Hapus</a>
                                    </div>
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
