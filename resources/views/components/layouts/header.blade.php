<!-- MAIN HEADER -->
<header
    class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 h-16 flex items-center justify-between px-6 shadow-sm transition-all duration-300 ease-in-out"
    :class="openSidebar ? 'ps-[calc((var(--spacing)_*_70)_+_24px)]' : 'ps-unset'" x-data="{
        timeAgo(date) {
            const now = new Date();
            const past = new Date(date);
            const diff = Math.floor((now - past) / 1000);
    
            if (diff < 60) return diff + ' detik lalu';
            if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
            if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
            if (diff < 2592000) return Math.floor(diff / 86400) + ' hari lalu';
    
            return past.toLocaleDateString();
        }
    }">
    <div class="flex items-center gap-4">
        <button @click="openSidebar = !openSidebar"
            class="text-gray-500 hover:text-indigo-600 transition-all duration-300 ease-in-out"
            :class="openSidebar ? 'rotate-180' : ''">
            <i class="ri-menu-unfold-line text-xl"></i>
        </button>
        <div class="relative hidden">
            <input type="text" placeholder="Cari laporan, proyek..."
                class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            <i class="ri-search-line absolute left-3 top-2 text-gray-400"></i>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <div x-data="{
            open: false,
            notifications: @js($globalNotifications)
        }" class="relative">

            <!-- 🔔 BUTTON -->
            <button @click="open = !open"
                class="relative p-2 rounded-full bg-white shadow hover:shadow-md hover:bg-gray-50 transition">

                <i class="ri-notification-3-line text-xl text-gray-700"></i>

                <!-- Badge -->
                <span x-show="notifications.filter(n => !n.read).length"
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-semibold"
                    x-text="notifications.filter(n => !n.read).length">
                </span>
            </button>

            <!-- 🔔 DROPDOWN -->
            <div x-show="open" @click.outside="open = false" x-transition
    class="md:absolute fixed right-0 mt-3 md:w-96 w-full md:left-[unset] left-0 
           bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-100 z-[99]">

    <!-- HEADER -->
    <div class="flex items-center justify-between px-5 py-4 border-b">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="ri-notification-3-fill text-indigo-500 text-lg"></i>
            Notifikasi
        </h3>

        <button class="text-xs text-indigo-600 hover:underline">
            Tandai semua
        </button>
    </div>

    <!-- LIST -->
    <div class="max-h-96 overflow-y-auto space-y-1 p-2">

        <template x-for="notif in notifications" :key="notif.id" x-data="{
            reportUrl: '{{ route('daily-report.show', ':id') }}',
            apdUrl: '{{ route('tools.show', ':id') }}',

            getUrl(notif) {
                if (notif.type === 'report_created' || notif.type === 'report_validate') {
                    return this.reportUrl.replace(':id', notif.notifiable_id);
                }

                if (notif.type === 'apd_baru' || notif.type === 'apd_menipis' || notif.type === 'apd_validate') {
                    return this.apdUrl.replace(':id', notif.notifiable_id);
                }

                return '#';
            }
        }">

            <a :href="getUrl(notif)"
                class="flex items-start gap-3 p-4 rounded-xl transition-all duration-200 group
                       hover:bg-gray-50"
                :class="!notif.is_read ? 'bg-indigo-50/60' : ''">

                <!-- ICON -->
                <div class="flex-shrink-0">
                    <template x-if="notif.type === 'report_created'">
                        <div class="bg-blue-100 text-blue-600 p-2.5 rounded-xl">
                            <i class="ri-file-list-3-line text-lg"></i>
                        </div>
                    </template>

                    <template x-if="notif.type === 'report_validate'">
                        <div class="bg-yellow-100 text-yellow-600 p-2.5 rounded-xl">
                            <i class="ri-refresh-line text-lg"></i>
                        </div>
                    </template>

                    <template x-if="notif.type === 'success'">
                        <div class="bg-green-100 text-green-600 p-2.5 rounded-xl">
                            <i class="ri-checkbox-circle-line text-lg"></i>
                        </div>
                    </template>
                </div>

                <!-- CONTENT -->
                <div class="flex-1 min-w-0">
                    <!-- TITLE -->
                    <p class="text-sm font-semibold text-gray-800 leading-snug"
                        x-text="notif.title"></p>

                    <!-- MESSAGE (BARU) -->
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2"
                        x-text="notif.message ?? 'Tidak ada deskripsi tambahan'"></p>

                    <!-- FOOT INFO -->
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-xs text-gray-400"
                            x-text="timeAgo(notif.created_at)"></span>

                        <span class="text-[11px] text-indigo-500 opacity-0 group-hover:opacity-100 transition">
                            Lihat →
                        </span>
                    </div>
                </div>

                <!-- UNREAD DOT -->
                <div x-show="!notif.is_read"
                    class="w-2.5 h-2.5 bg-indigo-500 rounded-full mt-1 animate-pulse">
                </div>
            </a>

        </template>

    </div>

    <!-- FOOTER -->
    <div class="p-4 text-center border-t bg-white/50 backdrop-blur">
        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
            Lihat Semua Notifikasi →
        </a>
    </div>

</div>
        </div>
        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-2 bg-indigo-50 rounded-full px-3 py-1 hover:bg-indigo-100 cursor-pointer">
            <img src="https://i.pravatar.cc/40" class="w-8 h-8 rounded-full border border-white" alt="">
            <span class="font-medium text-sm text-indigo-700">{{ Auth::user()->name }}</span>
            <i class="ri-arrow-down-s-line text-indigo-500 hidden"></i>
        </a>
    </div>
</header>
<!-- END HEADER -->
