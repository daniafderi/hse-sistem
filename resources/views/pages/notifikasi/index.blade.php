<x-layouts.app title="Notifikasi">
<div class="min-h-screen bg-white rounded-xl shadow-md p-6"
     x-data="notificationApp(@js($globalNotifications ?? []))">

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

    <!-- LIST -->
    <div class="space-y-3">

        <template x-for="notif in filteredNotifications()" :key="notif.id">
            <div class="bg-white rounded-xl shadow-sm p-4 flex items-start justify-between 
                        hover:shadow-md transition group"
                 :class="!notif.is_read ? 'bg-indigo-50/60' : ''">

                <!-- LEFT -->
                <div class="flex gap-3">

                    <!-- ICON -->
                    <div>
                        <template x-if="notif.type === 'report_created'">
                            <div class="bg-blue-100 text-blue-600 p-2.5 rounded-xl">
                                <i class="ri-file-list-3-line"></i>
                            </div>
                        </template>

                        <template x-if="notif.type === 'report_validate'">
                            <div class="bg-yellow-100 text-yellow-600 p-2.5 rounded-xl">
                                <i class="ri-refresh-line"></i>
                            </div>
                        </template>

                        <template x-if="notif.type === 'success'">
                            <div class="bg-green-100 text-green-600 p-2.5 rounded-xl">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                        </template>
                    </div>

                    <!-- CONTENT -->
                    <div>
                        <p class="text-sm font-semibold text-gray-800"
                           x-text="notif.title"></p>

                        <p class="text-sm text-gray-600 mt-1 line-clamp-2"
                           x-text="notif.message ?? 'Tidak ada deskripsi'"></p>

                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs text-gray-400"
                                  x-text="timeAgo(notif.created_at)"></span>

                            <span x-show="!notif.is_read"
                                  class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                        </div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="flex items-center gap-2">

                    <!-- OPEN -->
                    <a :href="getUrl(notif)"
                       class="opacity-0 group-hover:opacity-100 text-indigo-500 text-sm transition">
                        Buka →
                    </a>

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

        <!-- EMPTY -->
        <div x-show="filteredNotifications().length === 0"
             class="text-center text-gray-500 py-10">
            <i class="ri-inbox-line text-4xl mb-2"></i>
            <p>Tidak ada notifikasi</p>
        </div>

    </div>
</div>

<script>
function notificationApp(initialData) {
    console.log(initialData);
    return {
        search: '',
        filter: 'all',

        reportUrl: '{{ route('daily-report.show', ':id') }}',
        apdUrl: '{{ route('tools.show', ':id') }}',

        notifications: initialData,

        getUrl(notif) {
            if (notif.type === 'report_created' || notif.type === 'report_validate') {
                return this.reportUrl.replace(':id', notif.notifiable_id);
            }

            if (notif.type.includes('apd')) {
                return this.apdUrl.replace(':id', notif.notifiable_id);
            }

            return '#';
        },

        filteredNotifications() {
            return this.notifications.filter(n => {
                const matchSearch =
                    n.title.toLowerCase().includes(this.search.toLowerCase()) ||
                    (n.message ?? '').toLowerCase().includes(this.search.toLowerCase());

                if (this.filter === 'unread') {
                    return !n.is_read && matchSearch;
                }

                return matchSearch;
            });
        },

        unreadCount() {
            return this.notifications.filter(n => !n.is_read).length;
        },

        markAsRead(id) {
            const notif = this.notifications.find(n => n.id === id);
            if (notif) notif.is_read = 1;
            console.log(initialData);
        },

        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        },

        timeAgo(date) {
            const seconds = Math.floor((new Date() - new Date(date)) / 1000);

            if (seconds < 60) return 'Baru saja';
            if (seconds < 3600) return Math.floor(seconds / 60) + ' menit lalu';
            if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam lalu';

            return Math.floor(seconds / 86400) + ' hari lalu';
        }
    }
}
</script>
</x-layouts.app>