<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Audit: ') . $audit->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900
                    
                    <!-- Informasi Dasar Audit -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 Audit</h3>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><strong>Judul:</strong> {{ $audit->judul }}</p>
                                <p><strong>Auditee:</strong> {{ $audit->auditee->name }}</p>
                            </div>
                            <div>
                                <p><strong>Auditor:</strong> {{ $audit->auditor->name }}</p>
                                <p><strong>Tanggal Jadwal:</strong> {{ \Carbon\Carbon::parse($audit->tanggal_jadwal)->isoFormat('D MMMM YYYY') }}</p>
                                <p><strong>Status:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst($audit->status) }}</span></p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200

                    <!-- Temuan & Tindak Lanjut dari Auditee -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 Audit & Tindak Lanjut Auditee</h3>
                        <div class="mt-4 space-y-4">
                            @if($audit->criteria->isNotEmpty())
                                @foreach($audit->criteria as $criterion)
                                    <div class="p-4 border rounded-lg bg-gray-50
                                        <p class="font-semibold">{{ $criterion->nama }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $criterion->deskripsi }}</p>
                                        
                                        <div class="mt-4 p-4 bg-blue-50 rounded-md">
                                            <h4 class="font-semibold text-sm">Catatan Auditor:</h4>
                                            <p class="text-sm italic">{{ $criterion->pivot->auditor_notes ?? 'Tidak ada catatan.' }}</p>
                                        </div>

                                        @php
                                            $statusColors = [
                                                'Open' => 'bg-red-100 text-red-800',
                                                'InProgress' => 'bg-yellow-100 text-yellow-800',
                                                'Closed' => 'bg-green-100 text-green-800',
                                            ];
                                            $statusColor = $statusColors[$criterion->pivot->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <div class="mt-4 p-4 bg-green-50 rounded-md border border-green-200
                                            <h4 class="font-semibold text-sm text-green-800 Lanjut oleh Auditee:</h4>
                                            <p class="text-sm mt-2"><strong>Status:</strong> 
                                                <span class="font-medium px-2 py-1 text-xs rounded-full {{ $statusColor }}">
                                                    {{ $criterion->pivot->status ?? 'Belum ada status' }}
                                                </span>
                                            </p>
                                            <p class="text-sm mt-2"><strong>Catatan Auditee:</strong></p>
                                            <p class="text-sm italic pl-2">{{ $criterion->pivot->auditee_notes ?? 'Tidak ada catatan.' }}</p>
                                            
                                            @if($criterion->pivot->auditee_attachment_path)
                                                <p class="text-sm mt-2">
                                                    <strong>Bukti Unggahan:</strong> 
                                                    <a href="{{ Storage::url($criterion->pivot->auditee_attachment_path) }}" target="_blank" class="text-indigo-600 hover:underline">Lihat File Bukti</a>
                                                </p>
                                            @else
                                                <p class="text-sm mt-2"><strong>Bukti Unggahan:</strong> Tidak ada file yang diunggah.</p>
                                            @endif
                                        </div>

                                        <!-- Auditor Review Section -->
                                        <div class="mt-4 pt-4 border-t border-gray-200
                                            <h4 class="font-semibold text-md text-gray-800 Auditor</h4>
                                            <form action="{{ route('auditor.reviews.store', ['audit' => $audit->id, 'criterion' => $criterion->id]) }}" method="POST">
                                                @csrf
                                                <div class="mt-3">
                                                    <label for="auditor_review_status_{{ $criterion->id }}" class="block text-sm font-medium text-gray-700 Review</label>
                                                    <select id="auditor_review_status_{{ $criterion->id }}" name="auditor_review_status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                        <option value="Pending" {{ $criterion->pivot->auditor_review_status == 'Pending' ? 'selected' : '' }}>Menunggu Review</option>
                                                        <option value="Approved" {{ $criterion->pivot->auditor_review_status == 'Approved' ? 'selected' : '' }}>Disetujui</option>
                                                        <option value="RevisionNeeded" {{ $criterion->pivot->auditor_review_status == 'RevisionNeeded' ? 'selected' : '' }}>Perlu Revisi</option>
                                                    </select>
                                                </div>

                                                <div class="mt-3">
                                                    <label for="auditor_review_notes_{{ $criterion->id }}" class="block text-sm font-medium text-gray-700 Review Auditor</label>
                                                    <textarea id="auditor_review_notes_{{ $criterion->id }}" name="auditor_review_notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 old('auditor_review_notes', $criterion->pivot->auditor_review_notes) }}</textarea>
                                                </div>

                                                <div class="mt-3 text-right">
                                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Simpan Review
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>Belum ada kriteria atau temuan yang ditambahkan untuk audit ini.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
