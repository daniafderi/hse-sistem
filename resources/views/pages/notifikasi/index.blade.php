<x-layouts.app title="Notifikasi">
    <div class="min-h-screen bg-gray-100 p-6"
     x-data="notificationApp()">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="ri-notification-3-line text-indigo-600"></i>
            Notifikasi
        </h1>

        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm">
            <span x-text="unreadCount()"></span> Belum Dibaca
        </span>
    </div>

    <!-- SEARCH & FILTER -->
    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <input type="text"
               x-model="search"
               placeholder="Cari notifikasi..."
               class="w-full md:w-1/2 px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">

        <div class="flex gap-2">
            <button @click="filter = 'all'"
                :class="filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-white'"
                class="px-4 py-2 rounded-lg text-sm border">
                Semua
            </button>

            <button @click="filter = 'unread'"
                :class="filter === 'unread' ? 'bg-indigo-600 text-white' : 'bg-white'"
                class="px-4 py-2 rounded-lg text-sm border">
                Belum Dibaca
            </button>
        </div>
    </div>

    <!-- LIST NOTIF -->
    <div class="space-y-4">

        <template x-for="notif in filteredNotifications()" :key="notif.id">
            <div class="bg-white rounded-xl shadow p-4 flex items-start justify-between hover:shadow-md transition">

                <!-- LEFT -->
                <div class="flex gap-3">
                    <div class="mt-1">
                        <i :class="notif.read ? 'ri-checkbox-circle-line text-gray-400' : 'ri-error-warning-line text-indigo-500'"
                           class="text-xl"></i>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-800"
                           x-text="notif.title"></p>

                        <p class="text-sm text-gray-600"
                           x-text="notif.message"></p>

                        <span class="text-xs text-gray-400"
                              x-text="notif.time"></span>
                    </div>
                </div>

                <!-- RIGHT ACTION -->
                <div class="flex items-center gap-2">

                    <!-- MARK READ -->
                    <button @click="markAsRead(notif.id)"
                        class="p-2 rounded hover:bg-green-100 text-green-600"
                        title="Tandai Dibaca">
                        <i class="ri-check-line"></i>
                    </button>

                    <!-- DELETE -->
                    <button @click="remove(notif.id)"
                        class="p-2 rounded hover:bg-red-100 text-red-600"
                        title="Hapus">
                        <i class="ri-delete-bin-line"></i>
                    </button>

                </div>
            </div>
        </template>

        <!-- EMPTY STATE -->
        <div x-show="filteredNotifications().length === 0"
             class="text-center text-gray-500 py-10">
            <i class="ri-inbox-line text-4xl mb-2"></i>
            <p>Tidak ada notifikasi</p>
        </div>

    </div>
</div>

<script>
function notificationApp() {
    return {
        search: '',
        filter: 'all',

        notifications: [
            {
                id: 1,
                title: 'Laporan Baru',
                message: 'Laporan safety baru telah dibuat',
                time: '2 menit lalu',
                read: false
            },
            {
                id: 2,
                title: 'Data Diperbarui',
                message: 'Laporan berhasil diupdate',
                time: '10 menit lalu',
                read: true
            },
            {
                id: 3,
                title: 'Validasi Selesai',
                message: 'Laporan sudah divalidasi',
                time: '1 jam lalu',
                read: false
            }
        ],

        filteredNotifications() {
            return this.notifications.filter(n => {
                const matchSearch = n.title.toLowerCase().includes(this.search.toLowerCase());

                if (this.filter === 'unread') {
                    return !n.read && matchSearch;
                }

                return matchSearch;
            });
        },

        unreadCount() {
            return this.notifications.filter(n => !n.read).length;
        },

        markAsRead(id) {
            const notif = this.notifications.find(n => n.id === id);
            if (notif) notif.read = true;
        },

        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }
}
</script>
</x-layouts.app>