@props(['title'])

<!DOCTYPE html>
<html lang="en">
<x-layouts.head title="{{ $title }}"></x-layouts.head>

<body class="flex flex-col w-full min-h-[100vh]" x-data="{
        openSidebar: true,
        isMobile: window.innerWidth < 1024,

        init() {
            this.openSidebar = !this.isMobile

            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 1024
                this.openSidebar = !this.isMobile
            })
        }
    }">

    <x-layouts.sidebar></x-layouts.sidebar>
    <x-layouts.header></x-layouts.header>

    <div class="bg-[#f1f1f1] flex flex-col flex-grow-1 transition-all duration-300 ease-in-out pt-16"
        :class="openSidebar ? 'ps-0 lg:ps-[calc(var(--spacing)_*_70)]' : 'ps-0'">

        <div class="flex justify-between flex-col flex-1 p-[calc(var(--spacing)_*_6)]">
            <main class="flex gap-[calc(var(--spacing)_*_6)] flex-1 flex-col">
                {{ $slot }}
            </main>

            <x-layouts.footer></x-layouts.footer>
        </div>
    </div>

    <x-layouts.footer-script></x-layouts.footer-script>
</body>
</html>
