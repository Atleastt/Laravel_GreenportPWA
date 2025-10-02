<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Sub-Kriteria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white">
                    <form action="{{ route('subkriteria.update', $subkriteria) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <label for="kriteria_id" class="block text-sm font-medium text-gray-700">{{ __('Kriteria Induk') }}</label>
                                <select name="kriteria_id" id="kriteria_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                    @foreach ($kriterias as $kriteria)
                                        <option value="{{ $kriteria->id }}" {{ old('kriteria_id', $subkriteria->kriteria_id) == $kriteria->id ? 'selected' : '' }}>{{ $kriteria->nama_kriteria }}</option>
                                    @endforeach
                                </select>
                                @error('kriteria_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="nama_sub_kriteria" class="block text-sm font-medium text-gray-700">{{ __('Nama Sub-Kriteria') }}</label>
                                <input type="text" name="nama_sub_kriteria" id="nama_sub_kriteria" value="{{ old('nama_sub_kriteria', $subkriteria->nama_subkriteria) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                @error('nama_sub_kriteria')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="deskripsi_sub_kriteria" class="block text-sm font-medium text-gray-700">{{ __('Deskripsi') }}</label>
                                <textarea name="deskripsi_sub_kriteria" id="deskripsi_sub_kriteria" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">{{ old('deskripsi_sub_kriteria', $subkriteria->deskripsi_subkriteria) }}</textarea>
                                @error('deskripsi_sub_kriteria')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="flex justify-end mt-8 pt-5 border-t border-gray-200">
                            <a href="{{ route('kriteria.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">{{ __('Batal') }}</a>
                            <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-emerald-700">{{ __('Simpan Perubahan') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 