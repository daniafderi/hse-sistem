{{-- resources/views/auth/login.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login — Aplikasi</title>

  {{-- Jika pakai Vite (Laravel 9/10+ dengan Vite) --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- Jika belum pakai Vite, gunakan Tailwind CDN untuk cepat --}}
  <style>
    /* Optional small tweaks */
    .gradient-bg {
      background: linear-gradient(180deg, rgba(99,102,241,0.12) 0%, rgba(59,130,246,0.06) 100%);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-50">

  <div class="min-h-screen w-full flex items-center justify-center px-4 py-12">
    <div class="max-w-4xl w-full grid lg:grid-cols-2 gap-10 items-center">

      {{-- LEFT: Hero / Branding --}}
      <div class="hidden lg:flex flex-col justify-center pl-8">
        <div class="mb-6">
          <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-600 to-blue-500 flex items-center justify-center text-white text-lg font-bold shadow-lg">
              A
            </div>
            <div>
              <h1 class="text-2xl font-semibold text-gray-800">Safe<span class="text-indigo-600">Gard</span></h1>
              <p class="text-sm text-gray-500">Solusi laporan harian & kolaborasi tim</p>
            </div>
          </a>
        </div>

        <div class="bg-white gradient-bg p-6 rounded-2xl shadow-md border border-gray-100">
          <h2 class="text-lg font-semibold text-gray-800 mb-2">Selamat datang kembali 👋</h2>
          <p class="text-sm text-gray-600">Masuk untuk mengelola laporan, meninjau foto, dan bekerja sama dengan tim.</p>

          <div class="mt-6 grid gap-4">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">✓</div>
              <div>
                <p class="text-sm font-medium text-gray-700">Enkripsi & Privasi</p>
                <p class="text-xs text-gray-500">Data aman dan hanya dapat diakses oleh kontributor terdaftar.</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">⚡</div>
              <div>
                <p class="text-sm font-medium text-gray-700">Cepat & Ringan</p>
                <p class="text-xs text-gray-500">Interface ringan dan mudah digunakan di desktop dan mobile.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- RIGHT: Login card --}}
      <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
          <div class="mb-6">
            <h3 class="text-2xl font-semibold text-gray-800">Masuk ke akun Anda</h3>
            <p class="text-sm text-gray-500 mt-1">Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 font-medium">Daftar</a></p>
          </div>

          {{-- menampilkan pesan error umum --}}
          @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-100 text-red-700 rounded">
              <ul class="text-sm space-y-1">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            {{-- Email --}}
            <div class="mb-4">
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="appearance-none block w-full px-4 py-2 border rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-300 ring-1 ring-red-300 @enderror"
                placeholder="name@contoh.com"
              >
              @error('email')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Password with show/hide --}}
            <div class="mb-4" x-data="{ show: false }">
              <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
              <div class="relative">
                <input
                  id="password"
                  name="password"
                  :type="show ? 'text' : 'password'"
                  required
                  class="appearance-none block w-full pr-10 px-4 py-2 border rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('password') border-red-300 ring-1 ring-red-300 @enderror"
                  placeholder="Masukkan password"
                >
                <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-2 px-2 flex items-center text-gray-500">
                  <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.94 6.94A10.97 10.97 0 0110 4c4.97 0 9 3.58 9 8 0 1.02-.17 1.99-.49 2.88l-1.63-1.63A7.965 7.965 0 0018 12c0-3.31-3.58-6-8-6-.87 0-1.7.12-2.48.34L2.94 6.94zM3.5 4.5l12 12L17 19l-12-12L3.5 4.5z"/>
                  </svg>
                  <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 4c-4.97 0-9 3.58-9 8a10.97 10.97 0 001.06 3.36L1 17l1.06.64A9 9 0 0010 20c4.42 0 8.5-2.8 9-6.5C19 7.58 14.97 4 10 4z"/>
                  </svg>
                </button>
              </div>
              @error('password')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Remember & forgot --}}
            <div class="flex items-center justify-between mb-6">
              <label class="inline-flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember_me" class="w-4 h-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
                <span class="text-sm text-gray-600">Ingat saya</span>
              </label>

              <div>
                @if (Route::has('password.request'))
                  <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">Lupa password?</a>
                @endif
              </div>
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium transition">
              {{-- icon --}}
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
              </svg>
              Masuk
            </button>

          </form>

          {{-- small footer --}}
          <p class="mt-6 text-center text-xs text-gray-400">
            Dengan masuk, kamu setuju dengan <a href="#" class="text-indigo-600 underline">Syarat & Ketentuan</a>.
          </p>
        </div>
      </div>

    </div>
  </div>
</body>
</html>
