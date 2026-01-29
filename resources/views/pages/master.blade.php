<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body class="flex flex-col w-full min-h-[100vh]">
  @include('layouts.sidebar')
  @include('layouts.header')  
  <!-- MAIN CONTENT -->
   <div class="ps-[calc(var(--spacing)_*_70)] bg-[#f1f1f1] flex flex-col flex-grow-1">
      <div class="flex justify-between flex-col flex-1 p-[calc(var(--spacing)_*_6)]">
          <main class="flex gap-[calc(var(--spacing)_*_6)] flex-1 flex-col [--gap:calc(var(--spacing)_*_6)]">
              @yield('content')
          </main>
          @include('layouts.footer')            
      </div>
   </div>
</body>
</html>