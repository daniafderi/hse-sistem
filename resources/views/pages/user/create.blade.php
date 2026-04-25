<x-layouts.app title="Tambah User">
    <x-title-page>Tambah User</x-title-page>
    <form action="{{ route('user.store') }}" method="POST">
        @csrf
        @method('POST')
        <div class="bg-white shadow-md rounded p-4 flex flex-col gap-4">
            <div class="inline-flex gap-1.5 flex-col">
                <x-input-label for="name" required="true">Nama</x-input-label>
                <x-input-form name="name" id="name" placeholder="Masukkan nama"></x-input-form>
            </div>
            <div class="inline-flex gap-1.5 flex-col">
                <x-input-label for="email" required="true">Email</x-input-label>
                <x-input-form name="email" type="email" id="email" placeholder="Masukkan email"></x-input-form>
            </div>
            <div class="inline-flex gap-1.5 flex-col">
                <x-input-label for="role" required="true">Role</x-input-label>
                <select class="border-1 border-solid border-slate-300 rounded text-sm w-full" name="role" id="role">
                    <option value="Supervisor">Supervisor</option>
                    <option value="HSE Admin">HSE Admin</option>
                    <option value="HSE Lapangan">HSE Lapangan</option>
                    <option value="HSE Kantor">HSE Kantor</option>
                </select>
            </div>
            <div class="inline-flex gap-1.5 flex-col">
                <x-input-label for="password" required="true">Password</x-input-label>
                <x-input-form name="password" id="password" type="password" autocomplete="new-password"></x-input-form>
            </div>
            <div class="inline-flex gap-1.5 flex-col">
                <x-input-label for="password_confirmation" required="true">Konfirmasi Password</x-input-label>
                <x-input-form name="password_confirmation" id="password_confirmation" type="password" autocomplete="new-password"></x-input-form>
            </div>
            <div class="flex justify-end gap-2.5">
                <a href="{{ url()->previous() }}"
                    class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 px-4 py-2 rounded-lg text-sm shadow-sm transition">
                    Batal
                </a>

                <button type="submit"
                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition">
                    <i class="ri-send-plane-line mr-2"></i> Submit
                </button>
            </div>
        </div>
    </form>
</x-layouts>