<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Informasi Umum Audit -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 Audit</h3>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 Audit</p>
                            <p class="font-semibold text-gray-800 Sistem Informasi</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500
                            <p class="font-semibold text-gray-800 IT</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500
                            <p class="font-semibold text-gray-800 Juni 2024 - 30 Juni 2024</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500
                            <p class="font-semibold text-green-600
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Temuan -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 Temuan</h3>
                <div class="mt-4 overflow-x-auto">
                    <p class="text-gray-600 ada temuan yang tercatat untuk audit ini.</p>
                    <!-- Contoh jika ada temuan -->
                    {{-- <table class="min-w-full divide-y divide-gray-200
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Temuan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900
                                <td class="px-6 py-4 text-sm text-gray-500 tidak terkonfigurasi dengan benar.</td>
                                <td class="px-6 py-4 text-sm text-green-600
                            </tr>
                        </tbody>
                    </table> --}}
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end space-x-4">
                <a href="{{ url()->previous() }}" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50
                <button class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Cetak Laporan</button>
            </div>

        </div>
    </div>
</x-app-layout>

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-700">Detail Riwayat/Dokumen</h1>
{{--        <a href="{{ route('history') }}" class="text-sm text-emerald-600 hover:text-emerald-500 flex items-center">--}}
{{--            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">--}}
{{--                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />--}}
{{--            </svg>--}}
{{--            Kembali ke Daftar Riwayat--}}
{{--        </a>--}}
    </div>

    @php
        // Data dummy untuk detail history, sesuaikan dengan ID jika perlu
        $detailHistory = [
            'id' => request()->get('id', 1), // Ambil ID dari request atau default 1
            'nama_dokumen' => 'Laporan Audit Kepatuhan Lingkungan Q1 2024.pdf',
            'indikator' => 'Kepatuhan Regulasi Limbah Cair',
            'bobot' => '15%',
            'kriteria' => 'Pengelolaan Lingkungan',
            'file_url' => '#', // URL dummy ke file
            'auditor' => 'Ahmad Subarjo',
            'auditee' => 'PT Pelabuhan Jaya',
            'tanggal_audit' => '2024-03-15',
            'catatan' => 'Audit berjalan lancar, beberapa temuan minor terkait pencatatan.'
        ];
    @endphp

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Dokumen</label>
            <p class="mt-1 text-sm text-gray-900 p-2 border border-gray-200 rounded-md bg-gray-50">{{ $detailHistory['nama_dokumen'] }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Indikator Terkait</label>
            <p class="mt-1 text-sm text-gray-900 p-2 border border-gray-200 rounded-md bg-gray-50">{{ $detailHistory['indikator'] }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Bobot Indikator</label>
            <p class="mt-1 text-sm text-gray-900 p-2 border border-gray-200 rounded-md bg-gray-50">{{ $detailHistory['bobot'] }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Kriteria Utama</label>
            <p class="mt-1 text-sm text-gray-900 p-2 border border-gray-200 rounded-md bg-gray-50">{{ $detailHistory['kriteria'] }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Auditor</label>
            <p class="mt-1 text-sm text-gray-900 p-2 border border-gray-200 rounded-md bg-gray-50">{{ $detailHistory['auditor'] }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Auditee</label>
            <p class="mt-1 text-sm text-gray-900 p-2 border border-gray-200 rounded-md bg-gray-50">{{ $detailHistory['auditee'] }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Audit/Pembuatan</label>
            <p class="mt-1 text-sm text-gray-900 p-2 border border-gray-200 rounded-md bg-gray-50">{{ $detailHistory['tanggal_audit'] }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Dokumen Terlampir</label>
            <div class="mt-1 p-2 border border-gray-200 rounded-md bg-gray-50">
                @if($detailHistory['file_url'] !== '#')
                <a href="{{ $detailHistory['file_url'] }}" target="_blank" class="text-emerald-600 hover:text-emerald-500 underline flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    </svg>
                    Lihat/Unduh {{ $detailHistory['nama_dokumen'] }}
                </a>
                @else
                <p class="text-sm text-gray-500">Tidak ada file terlampir.</p>
                @endif
            </div>
        </div>
         <div>
            <label class="block text-sm font-medium text-gray-700">Catatan/Deskripsi Tambahan</label>
            <p class="mt-1 text-sm text-gray-900 p-2 border border-gray-200 rounded-md bg-gray-50 h-20">{{ $detailHistory['catatan'] }}</p>
            </div>
        </div>

    <div class="mt-8 flex justify-end">
        <a href="{{ route('history') }}" class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                Kembali
            </a>
        </div>
</div>
@endsection
