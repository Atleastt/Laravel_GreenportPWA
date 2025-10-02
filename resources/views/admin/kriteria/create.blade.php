<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Formulir Kriteria Baru</h1>

                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                            <p class="font-bold">Oops! Terjadi kesalahan.</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.kriteria.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="nama_kriteria" class="block text-sm font-medium text-gray-700">Nama Kriteria</label>
                                <input type="text" name="nama_kriteria" id="nama_kriteria" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" value="{{ old('nama_kriteria') }}" required placeholder="Contoh: Kepatuhan Regulasi">
                            </div>

                            <div>
                                <label for="deskripsi_kriteria" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="deskripsi_kriteria" id="deskripsi_kriteria" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" required placeholder="Jelaskan secara singkat mengenai kriteria ini...">{{ old('deskripsi_kriteria') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-5 border-t border-gray-200">
                            <a href="{{ route('admin.kriteria.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-25 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <button type="submit" class="ml-4 inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Kriteria
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
