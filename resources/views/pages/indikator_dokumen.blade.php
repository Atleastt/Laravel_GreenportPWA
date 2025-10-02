<x-app-layout>
    <div x-data="{ showSuccessMessage: {{ session('success') ? 'true' : 'false' }} }" x-init="setTimeout(() => showSuccessMessage = false, 5000)">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Success Message -->
                <div x-show="showSuccessMessage" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-90"
                    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md mb-6" role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="h-6 w-6 text-green-500 mr-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg></div>
                        <div>
                            <p class="font-bold">Berhasil!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Tambah Indikator -->
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambahkan Indikator Dokumen Baru</h1>
                        <form action="{{ route('indikator-dokumen.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label for="nama_indikator" class="block text-sm font-medium text-gray-700">Nama Indikator</label>
                                <input type="text" name="nama_indikator" id="nama_indikator" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" placeholder="Contoh: Kebersihan Area Dermaga" required>
                            </div>
                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" placeholder="Deskripsi singkat mengenai indikator ini"></textarea>
                            </div>
                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select id="kategori" name="kategori" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Environment">Environment</option>
                                    <option value="Safety">Safety</option>
                                    <option value="Operational">Operational</option>
                                </select>
                            </div>
                            <div class="flex items-center justify-end pt-5 border-t border-gray-200">
                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-25 transition ease-in-out duration-150">
                                    Simpan Indikator
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Daftar Indikator -->
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Indikator Dokumen</h1>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Indikator</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($indikatorDokumens as $indicator)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $indicator->nama_indikator }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $indicator->kategori }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($indicator->deskripsi, 70) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                <a href="{{ route('indikator-dokumen.edit', $indicator->id) }}" class="text-indigo-600 hover:text-indigo-900 focus:outline-none focus:underline">Edit</a>
                                                <form action="{{ route('indikator-dokumen.destroy', $indicator->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus indikator ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 focus:outline-none focus:underline">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="text-center py-12">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2H4a2 2 0 01-2-2z" />
                                                    </svg>
                                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak Ada Indikator</h3>
                                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan indikator dokumen baru.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $indikatorDokumens->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
