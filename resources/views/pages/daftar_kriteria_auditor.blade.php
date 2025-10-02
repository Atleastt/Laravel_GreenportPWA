<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Kriteria & Bobot') }}
            </h2>
            <a href="{{ route('kriteria.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-md shadow-sm text-sm">
                Tambah Kriteria Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kriteria</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub-Kriteria</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($kriterias as $kriteria)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $kriteria->nama_kriteria }}</div>
                                            <div class="text-sm text-gray-500">{{ $kriteria->deskripsi_kriteria }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <ul class="list-disc list-inside">
                                                @foreach ($kriteria->subkriterias as $sub)
                                                    <li class="flex justify-between items-center text-sm text-gray-700">
                                                        <span>{{ $sub->nama_subkriteria }}</span>
                                                        <span>
                                                            <a href="{{ route('subkriteria.edit', $sub) }}" class="text-indigo-600 hover:text-indigo-900 text-xs">Ubah</a>
                                                            <form action="{{ route('subkriteria.destroy', $sub) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Yakin ingin menghapus sub-kriteria ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Hapus</button>
                                                            </form>
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('kriteria.edit', $kriteria->id) }}" class="text-indigo-600 hover:text-indigo-900">Ubah</a>
                                            <form action="{{ route('kriteria.destroy', $kriteria->id) }}" method="POST" class="inline-block ml-4">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus kriteria ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            Belum ada kriteria yang ditambahkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
