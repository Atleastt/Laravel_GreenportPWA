<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Schedule a New Visit Form -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Jadwalkan Visitasi Lapangan Baru</h1>
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Berhasil</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    <form action="{{ route('visitasi.lapangan.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="auditor_name" class="block text-sm font-medium text-gray-700">Nama Auditor</label>
                                <input type="text" name="auditor_name" id="auditor_name" value="{{ Auth::user()->name ?? 'Auditor Terlogin' }}" readonly class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 sm:text-sm">
                            </div>
                            <div>
                                <label for="auditee_name" class="block text-sm font-medium text-gray-700">Nama Auditee</label>
                                <select id="auditee_name" name="auditee_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                    <option value="">Pilih Auditee</option>
                                    <option value="PT Pelabuhan Jaya">PT Pelabuhan Jaya</option>
                                    <option value="Terminal Petikemas Sentosa">Terminal Petikemas Sentosa</option>
                                    <option value="Gudang Logistik Bahari">Gudang Logistik Bahari</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="visit_date" class="block text-sm font-medium text-gray-700">Tanggal Visitasi</label>
                            <input type="date" name="visit_date" id="visit_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="visit_notes" class="block text-sm font-medium text-gray-700">Catatan Tambahan (Opsional)</label>
                            <textarea name="visit_notes" id="visit_notes" rows="3" placeholder="Contoh: Fokus pada pemeriksaan area gudang dan pengelolaan limbah." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"></textarea>
                        </div>
                        <div class="flex items-center justify-end pt-5 border-t border-gray-200">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-25 transition ease-in-out duration-150">
                                Jadwalkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Scheduled Visits Table -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Jadwal Visitasi</h1>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auditor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auditee</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">

                                @forelse ($jadwalVisitasi as $schedule)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($schedule->tanggal_visitasi)->isoFormat('dddd, D MMMM YYYY') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $schedule->auditor_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $schedule->auditee_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $schedule->status == 'Selesai' ? 'bg-green-100 text-green-800' : ($schedule->status == 'Dibatalkan' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ $schedule->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('visitasi.lapangan.show', $schedule) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                            <form action="{{ route('visitasi.lapangan.cancel', $schedule) }}" method="POST" class="inline">@csrf @method('PATCH')<button type="submit" class="text-red-600 hover:text-red-900">Batalkan</button></form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="text-center py-12">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Jadwal</h3>
                                                <p class="mt-1 text-sm text-gray-500">Mulai dengan menjadwalkan visitasi baru.</p>
                                            </div>
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
