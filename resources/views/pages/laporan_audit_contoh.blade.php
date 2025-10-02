<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Contoh Laporan Audit') }}
            </h2>
            <div class="flex space-x-2">
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-md shadow-sm text-sm">Cetak Laporan</button>
                <button class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-md shadow-sm text-sm">Unduh PDF</button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 space-y-8">

                    <!-- Kop Laporan -->
                    <div class="text-center border-b pb-4 border-gray-200
                        <h3 class="text-2xl font-bold">Laporan Hasil Audit Internal</h3>
                        <p class="text-md">PT. GreenPort Indonesia</p>
                        <p class="text-sm text-gray-500 Audit: 1 Januari 2024 - 30 Juni 2024</p>
                    </div>

                    <!-- Ringkasan Eksekutif -->
                    <section>
                        <h4 class="text-xl font-bold mb-4">1. Ringkasan Eksekutif</h4>
                        <p class="text-gray-700
                            Audit internal ini dilakukan untuk mengevaluasi kepatuhan terhadap standar operasional prosedur (SOP) di departemen operasional. Secara keseluruhan, departemen telah menunjukkan kepatuhan yang baik, namun ditemukan beberapa area yang memerlukan perbaikan segera untuk mitigasi risiko.
                        </p>
                    </section>

                    <!-- Temuan Audit -->
                    <section>
                        <h4 class="text-xl font-bold mb-4">2. Temuan Audit</h4>
                        <div class="space-y-4">
                            <!-- Temuan Mayor -->
                            <div class="p-4 border-l-4 border-red-500 bg-red-50 rounded-r-lg">
                                <h5 class="font-semibold text-red-800 Mayor: TM-001</h5>
                                <p class="mt-1 text-gray-700 ketidaksesuaian dalam pencatatan logistik barang masuk yang tidak sesuai dengan SOP-LOG-05. Terdapat 5 dari 50 sampel (10%) yang tidak memiliki tanda tangan supervisor.</p>
                            </div>
                            <!-- Temuan Minor -->
                            <div class="p-4 border-l-4 border-yellow-500 bg-yellow-50 rounded-r-lg">
                                <h5 class="font-semibold text-yellow-800 Minor: TN-001</h5>
                                <p class="mt-1 text-gray-700 permintaan ATK tidak diisi lengkap pada kolom tanggal oleh beberapa staf. Hal ini berpotensi menyebabkan kesalahan dalam pelacakan inventaris.</p>
                            </div>
                            <div class="p-4 border-l-4 border-yellow-500 bg-yellow-50 rounded-r-lg">
                                <h5 class="font-semibold text-yellow-800 Minor: TN-002</h5>
                                <p class="mt-1 text-gray-700 pemeliharaan preventif untuk peralatan kantor tidak dipajang di area yang mudah terlihat.</p>
                            </div>
                        </div>
                    </section>

                    <!-- Rekomendasi -->
                    <section>
                        <h4 class="text-xl font-bold mb-4">3. Rekomendasi</h4>
                        <ul class="list-disc list-inside space-y-2 text-gray-700
                            <li>
                                <span class="font-semibold">[Untuk TM-001]:</span> Melakukan pelatihan ulang kepada seluruh staf logistik mengenai SOP-LOG-05 dan pentingnya validasi supervisor. Batas waktu: 2 minggu.
                            </li>
                            <li>
                                <span class="font-semibold">[Untuk TN-001]:</span> Mengirimkan memo internal sebagai pengingat untuk melengkapi semua formulir secara akurat.
                            </li>
                             <li>
                                <span class="font-semibold">[Untuk TN-002]:</span> Segera mencetak dan memasang jadwal pemeliharaan di papan pengumuman departemen.
                            </li>
                        </ul>
                    </section>

                    <!-- Tanda Tangan -->
                    <div class="pt-8 flex justify-between text-center">
                        <div>
                            <p>Disusun oleh,</p>
                            <div class="mt-16 border-t border-gray-400 w-48"></div>
                            <p class="mt-2 font-semibold">Nama Auditor</p>
                            <p class="text-sm">Auditor Internal</p>
                        </div>
                        <div>
                            <p>Mengetahui,</p>
                            <div class="mt-16 border-t border-gray-400 w-48"></div>
                            <p class="mt-2 font-semibold">Nama Kepala Audit</p>
                            <p class="text-sm">Kepala Audit Internal</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@section('content')
    <h1 class="text-2xl font-semibold text-gray-700 mb-4">Laporan Audit (Contoh)</h1>
    <p class="text-gray-600">Ini adalah placeholder untuk halaman Laporan Audit Contoh.</p>
    <p class="text-gray-600 mt-2">Konten spesifik akan ditambahkan di sini berdasarkan Figma jika ada desainnya, termasuk tampilan detail laporan.</p>
@endsection
