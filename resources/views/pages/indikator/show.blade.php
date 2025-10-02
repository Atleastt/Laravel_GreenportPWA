<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900
                    <h2 class="text-2xl font-semibold mb-4">Detail Indikator</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-medium text-gray-500
                            <p class="mt-1 text-lg">{{ $indikator->subkriteria->kriteria->nama_kriteria }}</p>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-500 Kriteria</h3>
                            <p class="mt-1 text-lg">{{ $indikator->subkriteria->nama_subkriteria }}</p>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-500 Indikator</h3>
                            <p class="mt-1 text-lg">{{ $indikator->teks_indikator }}</p>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-500
                            <p class="mt-1 text-lg">{{ $indikator->bobot_indikator }}%</p>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-500 Maksimal</h3>
                            <p class="mt-1 text-lg">{{ $indikator->poin_maks_indikator }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-4 py-2 rounded-md shadow-sm">
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
