<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <h3 class="text-lg font-medium text-gray-900">Jadwal Audit Mendatang</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $upcomingAudits }}</p>
                    <p class="text-sm text-gray-500">Audit terjadwal</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <h3 class="text-lg font-medium text-gray-900">Dokumen Diminta</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingDocuments }}</p>
                    <p class="text-sm text-gray-500">Menunggu diunggah</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <h3 class="text-lg font-medium text-gray-900">Temuan Terbuka</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $openFindings }}</p>
                    <p class="text-sm text-gray-500">Memerlukan tindakan</p>
                </div>
            </div>

            <!-- Tabel Aktivitas Terbaru -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Aktivitas Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15 Juli 2024</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Permintaan dokumen untuk Audit K3</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 Juli 2024</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Anda mengunggah bukti untuk temuan TM-001</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">01 Juli 2024</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jadwal audit baru telah ditambahkan: Audit Keuangan H1</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Informasi</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
