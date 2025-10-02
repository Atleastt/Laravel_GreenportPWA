<x-app-layout>
    @php
        // Placeholder data agar halaman mockup tidak error ketika belum ada data audit nyata
        $audit = $audit ?? (object) [
            'title' => 'Judul Audit Contoh',
            'scheduled_start_date' => now(),
            'scheduled_end_date'   => now()->addDays(7),
            'auditor' => (object) ['full_name' => 'Nama Auditor'],
            'criteria' => collect(),
        ];
        $laporan = $laporan ?? null;
    @endphp

    <div class="py-12" x-data="{ tab: 'temuan' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Info Audit -->
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Nama Audit</h3>
                        <p class="mt-1 text-md font-semibold text-gray-900">{{ $audit->title }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Periode</h3>
                        <p class="mt-1 text-md font-semibold text-gray-900">{{ \Carbon\Carbon::parse($audit->scheduled_start_date)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($audit->scheduled_end_date)->translatedFormat('d M Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Auditor Utama</h3>
                        <p class="mt-1 text-md font-semibold text-gray-900">{{ $audit->auditor->full_name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigasi Tab -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-6 px-6" aria-label="Tabs">
                        <a href="#"
                           @click.prevent="tab = 'temuan'"
                           :class="{'border-indigo-500 text-indigo-600': tab === 'temuan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'temuan'}"
                           class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Temuan & Tindak Lanjut
                        </a>
                        <a href="#"
                           @click.prevent="tab = 'dokumen'"
                           :class="{'border-indigo-500 text-indigo-600': tab === 'dokumen', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'dokumen'}"
                           class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Dokumen Terkait
                        </a>
                        @if ($laporan)
                        <a href="#"
                           @click.prevent="tab = 'laporan'"
                           :class="{'border-indigo-500 text-indigo-600': tab === 'laporan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'laporan'}"
                           class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Laporan Audit
                        </a>
                        @endif
                    </nav>
                </div>

                <!-- Konten Tab -->
                <div class="p-6">
                    <!-- Tab Temuan -->
                    <div x-show="tab === 'temuan'" class="space-y-4">
                        @forelse ($audit->criteria as $criterion)
                            @php
                                $statusClass = match($criterion->pivot->status) {
                                    'Open' => 'border-red-500 bg-red-50',
                                    'InProgress' => 'border-yellow-500 bg-yellow-50',
                                    'Closed' => 'border-green-500 bg-green-50',
                                    default => 'border-gray-500 bg-gray-50',
                                };
                                $statusTextClass = match($criterion->pivot->status) {
                                    'Open' => 'text-red-800',
                                    'InProgress' => 'text-yellow-800',
                                    'Closed' => 'text-green-800',
                                    default => 'text-gray-800',
                                };
                            @endphp
                            <div class="p-4 border-l-4 {{ $statusClass }} rounded-r-lg">
                                <h5 class="font-semibold {{ $statusTextClass }}">{{ $criterion->teks_indikator }}</h5>
                                <p class="mt-1 text-sm text-gray-600"><strong>Catatan Awal Auditor:</strong> {{ $criterion->pivot->auditor_notes ?? 'Tidak ada catatan.' }}</p>

                                <!-- Auditee Follow-up Section -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-700">Tindak Lanjut Anda</h4>
                                            <p class="text-sm mt-1"><strong>Status:</strong> {{ $criterion->pivot->status ?? 'Belum ada tindak lanjut' }}</p>
                                        </div>
                                        @if ($criterion->pivot->status !== 'Closed')
                                            <a href="{{ route('auditee.tindak_lanjut.edit', ['audit' => $audit->id, 'criterion' => $criterion->id]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Update Tindak Lanjut</a>
                                        @else
                                            <span class="text-sm font-medium text-green-600">Tindak Lanjut Selesai</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Auditor Review Section -->
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-700">Hasil Review Auditor</h4>
                                    @php
                                        $reviewStatus = $criterion->pivot->auditor_review_status ?? 'Pending';
                                        $reviewStatusColors = [
                                            'Pending' => 'bg-gray-100 text-gray-800',
                                            'Approved' => 'bg-green-100 text-green-800',
                                            'RevisionNeeded' => 'bg-yellow-100 text-yellow-800',
                                        ];
                                        $reviewStatusColor = $reviewStatusColors[$reviewStatus];
                                    @endphp
                                    <p class="text-sm mt-1"><strong>Status Review:</strong> 
                                        <span class="font-medium px-2 py-1 text-xs rounded-full {{ $reviewStatusColor }}">
                                            {{ str_replace('Needed', ' Diperlukan', $reviewStatus) }}
                                        </span>
                                    </p>
                                    <p class="text-sm mt-1"><strong>Catatan dari Auditor:</strong></p>
                                    <p class="text-sm italic pl-2">{{ $criterion->pivot->auditor_review_notes ?? 'Belum ada review.' }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">Belum ada temuan atau kriteria untuk audit ini.</p>
                        @endforelse
                    </div>

                    <!-- Tab Dokumen -->
                    <div x-show="tab === 'dokumen'" class="hidden">
                       <p class="text-gray-700">Daftar dokumen yang diminta dan diunggah akan muncul di sini.</p>
                    </div>

                    <!-- Tab Laporan -->
                    @if ($laporan)
                    <div x-show="tab === 'laporan'" class="hidden space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $laporan->title }}</h3>
                        <div>
                            <h4 class="font-semibold text-gray-700">Ringkasan Eksekutif</h4>
                            <p class="mt-1 text-gray-600">{{ $laporan->executive_summary ?? 'Tidak ada ringkasan.' }}</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-700">Temuan dan Rekomendasi</h4>
                            <p class="mt-1 text-gray-600">{{ $laporan->findings_recommendations ?? 'Tidak ada temuan.' }}</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-700">Skor Kepatuhan</h4>
                            <p class="mt-1 text-lg font-bold text-green-600">{{ $laporan->compliance_score ?? 'N/A' }}%</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
