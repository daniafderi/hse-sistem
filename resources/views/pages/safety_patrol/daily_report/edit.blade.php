<x-layouts.app title="Edit Safety Report">
    <main class="flex flex-col gap-6 flex-1" x-data="reportForm(@js($projects), @js($report), @js($unsafeActions), @js($unsafeConditions))" x-init="updateDateRange();">

        @if (session('success'))
            <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-xl p-6">
            <form action="{{ route('daily-report.update', $report) }}" method="post" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-slate-800">Edit Laporan</h2>
                </div>

                <!-- Project & Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label required="true" for="project_safety_id">Project</x-input-label>
                        <select x-model="selectedProject" @change="updateDateRange" name="project_safety_id"
                            class="border border-slate-300 rounded-lg text-sm w-full p-2">

                            <option value="">--- Pilih Project ---</option>

                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ $report->project_safety_id == $project->id ? 'selected' : '' }}>
                                    {{ $project->nama }} - {{ $project->lokasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label required="true" for="tanggal">Tanggal</x-input-label>
                        <input type="date" name="tanggal"
    :min="minDate"
    :max="maxDate"
    value="{{ old('tanggal', $report->tanggal) }}"
    class="border border-slate-300 rounded-lg text-sm w-full p-2">
                    </div>
                </div>

                <!-- Permit, pekerja, jam -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <x-input-label required="true" for="permit">Permit</x-input-label>
                        <select name="permit" class="border border-slate-300 rounded-lg text-sm w-full p-2">
    @foreach ($permits as $permit)
        <option value="{{ $permit }}"
            {{ $report->permit == $permit ? 'selected' : '' }}>
            {{ $permit }}
        </option>
    @endforeach
</select>
                    </div>

                    <div>
                        <x-input-label required="true" for="jumlah_pekerja">Jumlah Pekerja</x-input-label>
                        <x-input-form type="number" min="0" value="{{ old('jumlah_pekerja', $report->jumlah_pekerja) }}" name="jumlah_pekerja"
                            class="!rounded-lg" id="jumlah_pekerja" />
                    </div>

                    <div>
                        <x-input-label required="true" for="jam_kerja">Jam Kerja</x-input-label>
                        <x-input-form type="number" min="0" value="{{ old('jam_kerja', $report->jam_kerja) }}" name="jam_kerja" class="!rounded-lg"
                            id="jam_kerja" />
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <x-input-label for="deskripsi">Deskripsi (opsional)</x-input-label>
                    <textarea name="deskripsi" id="deskripsi" class="border border-slate-300 rounded-lg text-sm w-full p-2 h-24">{{ old('deskripsi', $report->deskripsi) }}</textarea>
                </div>

                <hr class="border-slate-200">

                <!-- Safety Lapangan -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Safety Lapangan</h3>
                    <x-checkbox-group name="users" :options="$users" :current-user="auth()->user()" label="Kontributor Laporan" :selected="$report->users->pluck('id')->toArray()"/>
                </div>

                <hr class="border-slate-200">

                <!-- Unsafe Action -->
                <div x-data>
                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Unsafe Action</h3>

                    <template x-for="(item, index) in unsafeActions" :key="index">
                        <div class="mb-4 p-4 border rounded-lg bg-slate-50">

                            <!-- Textarea -->
                            <label class="font-semibold text-sm block mb-1">
                                Deskripsi Unsafe Action #<span x-text="index + 1"></span>
                            </label>
                            <input type="text" class="border border-slate-300 rounded-lg text-sm w-full p-2"
                                :name="'unsafe_action[' + index + '][text]'" x-model="item.text"></input>

                            <!-- Upload Foto -->
                            <div class="mt-3">
                                <label class="font-semibold text-sm block mb-1">
                                    Upload Foto #<span x-text="index + 1"></span>
                                </label>

                                <input type="file" multiple accept="image/*"
                                    :name="'unsafe_action[' + index + '][images][]'"
                                    class="border border-slate-300 rounded-lg text-sm w-full p-2">
                                    <input type="hidden"
    :name="'unsafe_action[' + index + '][old_image]'"
    :value="item.image_url">
                                <template x-if="item.image_url">
    <div class="flex gap-2 mt-2">
            <img :src="'/storage/' + item.image_url" class="w-[150px] h-[150px] object-cover rounded">
    </div>
</template>
                            </div>

                            <div class="mt-3">
                                <label class="font-semibold text-sm block mb-1">
                                    Tindakan Perbaikan #<span x-text="index + 1"></span>
                                </label>

                                <input type="text" :name="'unsafe_action[' + index + '][tindakan_perbaikan]'"
                                    class="border border-slate-300 rounded-lg text-sm w-full p-2">
                            </div>

                            <!-- Delete -->
                            <button type="button" class="mt-2 text-sm text-red-600 hover:underline"
                                @click="unsafeActions.splice(index, 1)" x-show="unsafeActions.length > 0">
                                Hapus Item
                            </button>

                        </div>
                    </template>

                    <p class="text-sm text-slate-500 italic mb-3" x-show="unsafeActions.length === 0">
                        Belum ada Unsafe Action yang ditambahkan
                    </p>

                    <!-- BUTTON ADD -->
                    <button type="button"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow text-sm hover:bg-indigo-700"
                        @click="unsafeActions.push({ text: '', images: [], tindakan_perbaikan: '' })">
                        + Tambah Unsafe Action
                    </button>
                </div>


                <hr class="border-slate-200">

                <!-- Unsafe Condition -->
                <div x-data class="mt-8">
                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Unsafe Condition</h3>

                    <template x-for="(item, index) in unsafeConditions" :key="index">
                        <div class="mb-4 p-4 border rounded-lg bg-slate-50">

                            <!-- Textarea -->
                            <label class="font-semibold text-sm block mb-1">
                                Deskripsi Unsafe Condition #<span x-text="index + 1"></span>
                            </label>

                            <input type="text" class="border border-slate-300 rounded-lg text-sm w-full p-2"
                                :name="'unsafe_condition[' + index + '][text]'" x-model="item.text"></input>

                            <!-- Upload Foto -->
                            <div class="mt-3">
                                <label class="font-semibold text-sm block mb-1">
                                    Upload Foto #<span x-text="index + 1"></span>
                                </label>

                                <input type="file" multiple accept="image/*"
                                    :name="'unsafe_condition[' + index + '][images][]'"
                                    class="border border-slate-300 rounded-lg text-sm w-full p-2">
                                    <input type="hidden"
    :name="'unsafe_condition[' + index + '][old_image]'"
    :value="item.image_url">
                                    <template x-if="item.image_url">
    <div class="flex gap-2 mt-2">
            <img :src="'/storage/' + item.image_url" class="w-[150px] h-[150px] object-cover rounded">
    </div>
</template>
                            </div>

                            <div class="mt-3">
                                <label class="font-semibold text-sm block mb-1">
                                    Tindakan Perbaikan #<span x-text="index + 1"></span>
                                </label>

                                <input type="text" :name="'unsafe_condition[' + index + '][tindakan_perbaikan]'"
                                    class="border border-slate-300 rounded-lg text-sm w-full p-2">
                            </div>

                            <!-- Delete -->
                            <button type="button" class="mt-2 text-sm text-red-600 hover:underline"
                                @click="unsafeConditions.splice(index, 1)" x-show="unsafeConditions.length > 0">
                                Hapus Item
                            </button>

                        </div>
                    </template>

                    <p class="text-sm text-slate-500 italic mb-3" x-show="unsafeConditions.length === 0">
                        Belum ada Unsafe Condition yang ditambahkan
                    </p>

                    <!-- BUTTON ADD -->
                    <button type="button"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow text-sm hover:bg-indigo-700"
                        @click="unsafeConditions.push({ text: '', images: [], tindakan_perbaikan: '' })">
                        + Tambah Unsafe Condition
                    </button>
                </div>


                <hr class="border-slate-200">

                <!-- Reward, Punishment, dll -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="font-semibold" for="reward">Reward</label>
                        <textarea name="reward" id="reward" class="border border-slate-300 rounded-lg text-sm w-full p-2 h-24">{{ old('reward', $report->reward) }}</textarea>
                    </div>

                    <div>
                        <label class="font-semibold" for="punishment">Punishment</label>
                        <textarea name="punishment" id="punishment" class="border border-slate-300 rounded-lg text-sm w-full p-2 h-24">{{ old('punishment', $report->punishment) }}</textarea>
                    </div>

                    <div>
                        <label class="font-semibold" for="kecelakaan">Kecelakaan</label>
                        <textarea name="kecelakaan" id="kecelakaan" class="border border-slate-300 rounded-lg text-sm w-full p-2 h-24">{{ old('kecelakaan', $report->kecelakaan) }}</textarea>
                    </div>

                    <div>
                        <label class="font-semibold" for="nearmiss">Nearmiss</label>
                        <textarea name="nearmiss" id="nearmiss" class="border border-slate-300 rounded-lg text-sm w-full p-2 h-24">{{ old('nearmiss', $report->nearmiss) }}</textarea>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end pt-4 gap-2.5">
                    <a href="{{ url()->previous() }}" class="flex items-center gap-2 bg-white border border-gray-300 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 px-4 py-2 rounded-lg text-sm shadow-sm transition">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md transition">
                        <i class="ri-send-plane-line mr-2"></i> Submit
                    </button>
                </div>
            </form>


        </div>

    </main>
    <script>
        function reportForm(projects, report, unsafeActions, unsafeConditions) {
    return {
        projects,
        report,
        selectedProject: report.project_safety_id ?? '',
        minDate: '',
        maxDate: '',

        unsafeActions: unsafeActions  ?? [],
        unsafeConditions: unsafeConditions ?? [],

        updateDateRange() {
            const p = this.projects.find(pr => pr.id == this.selectedProject);

            if (p) {
                this.minDate = p.tanggal_mulai;
                this.maxDate = p.tanggal_selesai;
            }
        }
    };
}
    </script>

</x-layouts.app>
