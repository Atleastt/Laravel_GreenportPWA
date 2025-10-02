<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-black leading-tight text-center w-full mx-auto">
            {{ __('Update Tindak Lanjut Temuan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6 p-4 border-l-4 border-blue-500 bg-blue-50 rounded-r-lg">
                        <h5 class="font-semibold text-blue-800">{{ $criterion->teks_indikator }}</h5>
                        <p class="mt-1 text-sm text-gray-600"><strong>Catatan Auditor:</strong> {{ $criterion->pivot->auditor_notes ?? 'Tidak ada catatan.' }}</p>
                        <p class="mt-1 text-sm text-gray-600"><strong>Status Saat Ini:</strong> {{ $criterion->pivot->status }}</p>
                    </div>

                    <form method="POST" action="{{ route('auditee.tindak_lanjut.update', ['audit' => $audit->id, 'criterion' => $criterion->id]) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Ubah Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="InProgress" {{ $criterion->pivot->status == 'InProgress' ? 'selected' : '' }}>Dalam Pengerjaan (In Progress)</option>
                                <option value="Closed" {{ $criterion->pivot->status == 'Closed' ? 'selected' : '' }}>Selesai (Closed)</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Catatan Auditee -->
                        <div class="mt-4">
                            <x-input-label for="auditee_notes" :value="__('Catatan Tindak Lanjut Anda')" />
                            <textarea id="auditee_notes" name="auditee_notes" rows="4" class="block mt-1 w-full border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('auditee_notes', $criterion->pivot->auditee_notes) }}</textarea>
                            <x-input-error :messages="$errors->get('auditee_notes')" class="mt-2" />
                        </div>

                        <!-- File Bukti -->
                        <div class="mt-4">
                            <x-input-label for="bukti_file" :value="__('Unggah File Bukti Baru (Opsional)')" />
                            <input id="bukti_file" name="bukti_file" type="file" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <p class="mt-1 text-sm text-gray-500">Tipe file: PDF, JPG, PNG, DOC, DOCX. Maks: 10MB.</p>
                            @if ($criterion->pivot->auditee_attachment_path)
                                <p class="mt-2 text-sm text-gray-600">
                                    File saat ini: 
                                    <a href="{{ Storage::url($criterion->pivot->auditee_attachment_path) }}" target="_blank" class="text-indigo-600 hover:underline">Lihat Bukti</a>
                                </p>
                            @endif
                            <x-input-error :messages="$errors->get('bukti_file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('auditee.tugas.show', $audit->id) }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Batal') }}
                            </a>

                            <x-primary-button class="ms-4">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
