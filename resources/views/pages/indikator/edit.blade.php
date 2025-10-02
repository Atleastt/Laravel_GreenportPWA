<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900
                    <h2 class="text-2xl font-semibold mb-6">Ubah Indikator</h2>

                    <form action="{{ route('indikator.update', $indikator->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Sub Kriteria -->
                        <div class="mb-4">
                            <label for="subkriteria_id" class="block text-sm font-medium text-gray-700 Kriteria</label>
                            <select id="subkriteria_id" name="subkriteria_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 required>
                                <option value="">Pilih Sub Kriteria</option>
                                @foreach($subkriterias as $subkriteria)
                                    <option value="{{ $subkriteria->id }}" {{ old('subkriteria_id', $indikator->subkriteria_id) == $subkriteria->id ? 'selected' : '' }}>
                                        {{ $subkriteria->kriteria->nama_kriteria }} &raquo; {{ $subkriteria->nama_subkriteria }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subkriteria_id')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teks Indikator -->
                        <div class="mb-4">
                            <label for="teks_indikator" class="block text-sm font-medium text-gray-700 Indikator</label>
                            <textarea id="teks_indikator" name="teks_indikator" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 required>{{ old('teks_indikator', $indikator->teks_indikator) }}</textarea>
                            @error('teks_indikator')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bobot Indikator -->
                        <div class="mb-4">
                            <label for="bobot" class="block text-sm font-medium text-gray-700 (%)</label>
                            <input type="number" id="bobot" name="bobot" value="{{ old('bobot', $indikator->bobot) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 required step="0.01">
                            @error('bobot')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>



                        <!-- Tombol Aksi -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="bg-lime-500 hover:bg-lime-600 text-white font-semibold px-4 py-2 rounded-md shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
