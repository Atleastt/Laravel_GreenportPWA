<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Checklist Audit: ') }} {{ $audit->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('auditee.tugas.checklist.store', $audit) }}" method="POST" enctype="multipart/form-data"
                  class="bg-white p-8 rounded-lg shadow-md space-y-6">
                @csrf

                @foreach ($audit->criteria as $criterion)
                    <div class="mb-6 p-4 border-l-4 border-gray-300 bg-gray-50 dark:bg-gray-700 rounded-r-lg">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $criterion->teks_indikator }}</h4>

                        <!-- Status -->
                        <div class="mt-2">
                            <label for="status_{{ $criterion->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                            <select id="status_{{ $criterion->id }}" name="items[{{ $criterion->id }}][status]" class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-700 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach (['Open','InProgress','Closed'] as $status)
                                    <option value="{{ $status }}" {{ $criterion->pivot->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Catatan Auditee -->
                        <div class="mt-4">
                            <label for="notes_{{ $criterion->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Catatan Anda') }}</label>
                            <textarea id="notes_{{ $criterion->id }}" name="items[{{ $criterion->id }}][auditee_notes]" rows="3" class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-700 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old("items.{$criterion->id}.auditee_notes", $criterion->pivot->auditee_notes) }}</textarea>
                        </div>

                        <!-- Upload Bukti -->
                        <div class="mt-4">
                            <label for="attachment_{{ $criterion->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Unggah Bukti (Opsional)') }}</label>
                            <input type="file" id="attachment_{{ $criterion->id }}" name="items[{{ $criterion->id }}][attachment]" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:bg-gray-200 file:border-0 file:rounded-md">
                            @if ($criterion->pivot->auditee_attachment_path)
                                <p class="mt-1 text-sm">
                                    {{ __('File saat ini:') }}
                                    <a href="{{ Storage::url($criterion->pivot->auditee_attachment_path) }}" target="_blank" class="text-indigo-600 hover:underline">{{ __('Lihat Bukti') }}</a>
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end mt-6">
                    <a href="{{ route('auditee.tugas.show', $audit) }}" class="text-sm text-red-500 hover:text-gray-200 mr-4">{{ __('Batal') }}</a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-purple-600 border border-transparent rounded-md font-semibold text-sm text-black uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        {{ __('Simpan Checklist') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 