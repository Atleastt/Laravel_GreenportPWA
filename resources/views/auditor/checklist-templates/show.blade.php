<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Template') }}
            </h2>
            <a href="{{ route('auditor.checklist-templates.index') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 text-sm">
                &larr; {{ __('Kembali ke Daftar') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $checklistTemplate->nama_template }}</h3>
                        <p class="text-sm text-gray-500">{{ $checklistTemplate->created_at->translatedFormat('d F Y H:i') }}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300">{{ __('Deskripsi') }}</h4>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $checklistTemplate->deskripsi_template }}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300">{{ __('Pembuat') }}</h4>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $checklistTemplate->pembuat->full_name ?? __('N/A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 