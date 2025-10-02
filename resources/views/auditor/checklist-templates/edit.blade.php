<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Template Checklist: ') }} {{ $checklistTemplate->nama_template }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('auditor.checklist-templates.update', $checklistTemplate) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="nama_template" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Template') }}</label>
                            <input type="text" name="nama_template" id="nama_template" value="{{ old('nama_template', $checklistTemplate->nama_template) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nama_template') border-red-500 @enderror">
                            @error('nama_template')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="deskripsi_template" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Deskripsi Template') }}</label>
                            <textarea name="deskripsi_template" id="deskripsi_template" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('deskripsi_template') border-red-500 @enderror">{{ old('deskripsi_template', $checklistTemplate->deskripsi_template) }}</textarea>
                            @error('deskripsi_template')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('auditor.checklist-templates.index') }}" class="rounded-md border border-gray-300 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('Batal') }}</a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">{{ __('Update Template') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 