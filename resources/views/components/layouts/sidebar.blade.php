<aside :class="openSidebar ? 'translate-x-0' : 'translate-x-[calc(var(--spacing)_*_(-70))]'"
    class="fixed z-10 top-0 left-0 bottom-0 bg-white border-r border-gray-200 flex flex-col h-screen w-[calc(var(--spacing)_*_(70))] transition-all duration-300">

    <!-- 🔹 AREA MENU (scrollable) -->
    <div class="flex flex-col flex-1 overflow-y-auto">

        <div class="py-4 px-3">
            <!-- Logo -->
            <div class="flex items-center gap-2 mb-6 px-3">
                <i class="ri-shield-user-line text-2xl text-indigo-600"></i>
                <h1 class="text-lg font-semibold text-gray-800">Safe<span class="text-indigo-600">Gard</span></h1>
            </div>

            <div class="border-t border-gray-100 mb-3"></div>

            <!-- Main Menu -->
            <nav class="space-y-1">

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium 
                 hover:bg-indigo-50 hover:text-indigo-600 transition {{ Request::routeIs('dashboard') ? 'text-white bg-blue-600 ' : 'text-gray-700' }}">
                    <i class="ri-dashboard-line text-lg"></i> Dashboard
                </a>

                <!-- Aktivitas -->
                <a href="{{ route('activity.index') }}"
                    class="hidden flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                     hover:bg-indigo-50 hover:text-indigo-600 transition {{ Request::routeIs('activity.index') ? 'text-white bg-blue-600 ' : 'text-gray-700' }}">
                    <i class="ri-pulse-line text-lg"></i> Aktivitas
                </a>

                <div class="border-t border-gray-100 my-2"></div>

                <!-- 🔽 DROPDOWN: Safety Works -->
                @if (auth()->user()->can('isSupervisor') || auth()->user()->can('isHseLapangan'))
                <div x-data="{
                    open: @json(Request::routeIs('daily-report.*') ? true : (Request::routeIs('project.*') ? true : false))
                }">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium 
                        text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <span class="flex items-center gap-3">
                            <i class="ri-shield-check-line text-lg"></i> Safety Patrol
                        </span>
                        <i class="ri-arrow-down-s-line transition-transform duration-300"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-collapse class="pl-10 mt-2 space-y-2">
                        <a href="{{ route('project.index') }}"
                            class="block text-sm hover:text-indigo-600 {{ Request::routeIs('project.*') ? 'text-blue-600' : 'text-gray-600' }}">
                            Project Patrol
                        </a>
                        <a href="{{ route('daily-report.index') }}"
                            class="block text-sm hover:text-indigo-600 {{ Request::routeIs('daily-report.*') ? 'text-blue-600' : 'text-gray-600' }}">
                            Daily Report
                        </a>
                    </div>
                </div>

                @endif

                <!-- Briefing -->
                @can('isHseLapangan')
                <a href="{{ route('safety-briefing.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium 
                     hover:bg-indigo-50 hover:text-indigo-600 transition {{ Request::routeIs('safety-briefing.*') ? 'text-white bg-blue-600 ' : 'text-gray-700' }}">
                    <i class="ri-user-voice-line text-lg"></i> Safety Briefing
                </a>
                @endcan

                <div class="border-t border-gray-100 my-2"></div>

                <!-- 🔽 DROPDOWN: Equipment -->
                @if (auth()->user()->can('isHseKantor') || auth()->user()->can('isSupervisor'))
                <div x-data="{ open: @json(Request::routeIs('tools.*') ? true : (Request::routeIs('loans.*') ? true : false)) }" class="mt-2">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium 
                        text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <span class="flex items-center gap-3">
                            <i class="ri-tools-line text-lg"></i> Equipment
                        </span>
                        <i class="ri-arrow-down-s-line transition-transform duration-300"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-collapse class="pl-10 mt-2 space-y-2">
                        <a href="{{ route('tools.index') }}" class="block text-sm {{ Request::routeIs('tools.*') ? 'text-blue-600' : 'text-gray-600' }} hover:text-indigo-600">
                            Daftar APD
                        </a>
                        <a href="{{ route('loans.index') }}" class="block text-sm {{ Request::routeIs('loans.*') ? 'text-blue-600' : 'text-gray-600' }} hover:text-indigo-600">
                            Peminjaman
                        </a>
                    </div>
                </div>
                    
                @endif

                <!-- Briefing -->
                @can('isHseAdmin')
                <a href="{{ route('user.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium 
                    {{ Request::routeIs('user.*') ? 'text-white bg-blue-600 ' : 'text-gray-700' }} hover:bg-indigo-50 hover:text-indigo-600 transition">
                    <i class="ri-user-line text-lg"></i> User
                </a>
                    
                @endcan

                <!-- Reports -->
                <a href="{{ route('export.index') }}"
                    class="hidden items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium 
                    {{ Request::routeIs('export.*') ? 'text-white bg-blue-600 ' : 'text-gray-700' }} hover:bg-indigo-50 hover:text-indigo-600 transition">
                    <i class="ri-bar-chart-line text-lg"></i> Reports
                </a>

                <!-- 🔽 DROPDOWN: Safety Works -->
                @if (auth()->user()->can('isSupervisor') || auth()->user()->can('isHseAdmin'))
                <div x-data="{
                    open: @json(Request::routeIs('export.*') ? true : (Request::routeIs('project.*') ? true : false))
                }">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium 
                        text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <span class="flex items-center gap-3">
                            <i class="ri-bar-chart-line text-lg"></i> Report
                        </span>
                        <i class="ri-arrow-down-s-line transition-transform duration-300"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-collapse class="pl-10 mt-2 space-y-2">
                        <a href="{{ route('export.index') }}"
                            class="block text-sm hover:text-indigo-600 {{ Request::routeIs('export.*') ? 'text-blue-600' : 'text-gray-600' }}">
                            Safety Patrol Report
                        </a>
                        <a href="{{ route('tool.laporan') }}"
                            class="block text-sm hover:text-indigo-600 {{ Request::routeIs('tool.laporan') ? 'text-blue-600' : 'text-gray-600' }}">
                            APD Report
                        </a>
                    </div>
                </div>

                @endif

            </nav>
        </div>
    </div>

    <!-- 🧑 USER CARD (Tetap di bawah) -->
    <div class="border-t border-gray-100 p-4">
        <div class="bg-gradient-to-r from-indigo-500 to-blue-500 text-white rounded-xl p-4 flex flex-col">
            <div class="flex items-center gap-3 mb-3">
                <img src="https://i.pravatar.cc/50?u=dani" class="w-10 h-10 rounded-full border border-white shadow-sm">
                <div>
                    <h4 class="text-sm font-semibold">{{ Auth::user()->name }}</h4>
                    <p class="text-xs opacity-90">{{ Auth::user()->role }}</p>
                </div>
            </div>

            <p class="text-xs mb-3 opacity-90">Tetap pantau keselamatan tim dengan laporan harian.</p>

            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit"
                    class="flex items-center justify-center gap-2 w-full bg-white text-indigo-600 font-medium 
                    text-sm py-2 rounded-lg hover:bg-gray-50 transition">
                    <i class="ri-logout-box-line"></i> Logout
                </button>
            </form>
        </div>
    </div>

</aside>
