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
            <div>
                <button class="bg-blue-700 text-white rounded shadow-md outline-0 border-none py-2 px-3 w-full text-sm hover:bg-blue-600 cursor-pointer" type="submit">Submit</button>
            </div>
        </div>
    </form>
</x-layouts>