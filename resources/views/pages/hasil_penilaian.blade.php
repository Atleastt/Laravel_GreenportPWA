<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hasil Penilaian Audit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Hasil Penilaian Audit</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Berikut adalah hasil penilaian dari audit yang telah dilakukan terhadap unit kerja Anda.
                            </p>
                        </div>
                    </div>

                    @if($audits->count() > 0)
                        <!-- Tabel Hasil Penilaian -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Judul Audit
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Auditor
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Periode Audit
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Skor/Hasil
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($audits as $audit)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $audit->title }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ID: {{ $audit->id }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $audit->auditor->name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($audit->scheduled_start_date)->format('d M Y') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    s.d. {{ \Carbon\Carbon::parse($audit->scheduled_end_date)->format('d M Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($audit->status === 'Completed') bg-green-100 text-green-800
                                                    @elseif($audit->status === 'InProgress') bg-blue-100 text-blue-800
                                                    @elseif($audit->status === 'Scheduled') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    @switch($audit->status)
                                                        @case('Completed')
                                                            Selesai
                                                            @break
                                                        @case('InProgress')
                                                            Berlangsung
                                                            @break
                                                        @case('Scheduled')
                                                            Terjadwal
                                                            @break
                                                        @default
                                                            {{ $audit->status }}
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($audit->status === 'Completed')
                                                    <div class="text-sm text-gray-900">
                                                        <span class="font-semibold text-green-600">85/100</span>
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Memuaskan
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-500">Belum tersedia</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($audit->status === 'Completed')
                                                    <a href="{{ route('history.report', $audit) }}" 
                                                       class="text-emerald-600 hover:text-emerald-900 mr-3">
                                                        Lihat Laporan
                                                    </a>
                                                    <a href="{{ route('rekomendasi.index') }}?audit_id={{ $audit->id }}" 
                                                       class="text-blue-600 hover:text-blue-900">
                                                        Lihat Rekomendasi
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">Tidak tersedia</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $audits->links() }}
                        </div>

                        <!-- Summary Card -->
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-green-50 p-6 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-green-600">Audit Selesai</p>
                                        <p class="text-2xl font-semibold text-green-900">{{ $audits->where('status', 'Completed')->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 p-6 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-blue-600">Rata-rata Skor</p>
                                        <p class="text-2xl font-semibold text-blue-900">
                                            @if($audits->where('status', 'Completed')->count() > 0)
                                                85/100
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-yellow-50 p-6 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-yellow-600">Temuan Aktif</p>
                                        <p class="text-2xl font-semibold text-yellow-900">3</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Hasil Penilaian</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Belum ada audit yang selesai untuk unit kerja Anda.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('auditee.tugas.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Lihat Tugas Audit
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
