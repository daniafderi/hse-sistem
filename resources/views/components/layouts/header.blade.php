<!-- MAIN HEADER -->
 <header class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 h-16 flex items-center justify-between px-6 shadow-sm transition-all duration-300 ease-in-out" :class="openSidebar ? 'ps-[calc((var(--spacing)_*_70)_+_24px)]' : 'ps-unset'"> 
    <div class="flex items-center gap-4">
      <button @click="openSidebar = !openSidebar" class="text-gray-500 hover:text-indigo-600 transition-all duration-300 ease-in-out" :class="openSidebar ? 'rotate-180' : ''">
        <i class="ri-menu-unfold-line text-xl"></i>
      </button>
      <div class="relative hidden">
        <input type="text" placeholder="Cari laporan, proyek..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        <i class="ri-search-line absolute left-3 top-2 text-gray-400"></i>
      </div>
    </div>

    <div class="flex items-center gap-4">
      <button class="relative text-gray-500 hover:text-indigo-600 hidden">
        <i class="ri-notification-3-line text-xl"></i>
        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
      </button>
      <button class="text-gray-500 hover:text-indigo-600 hidden">
        <i class="ri-settings-3-line text-xl"></i>
      </button>
      <a href="{{route('profile.edit')}}" class="flex items-center gap-2 bg-indigo-50 rounded-full px-3 py-1 hover:bg-indigo-100 cursor-pointer">
        <img src="https://i.pravatar.cc/40" class="w-8 h-8 rounded-full border border-white" alt="">
        <span class="font-medium text-sm text-indigo-700">{{ Auth::user()->name }}</span>
        <i class="ri-arrow-down-s-line text-indigo-500 hidden"></i>
      </a>
    </div>
  </header>
<!-- END HEADER -->