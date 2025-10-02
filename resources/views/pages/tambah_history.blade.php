<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Catatan Riwayat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900
                    <form action="#" method="POST" class="space-y-6">
                        @csrf

                        <!-- Judul Peristiwa -->
                        <div>
                            <label for="judul_peristiwa" class="block text-sm font-medium text-gray-700 Peristiwa</label>
                            <input type="text" name="judul_peristiwa" id="judul_peristiwa" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: Rapat Kick-off Audit">
                        </div>

                        <!-- Audit Terkait -->
                        <div>
                            <label for="audit_terkait" class="block text-sm font-medium text-gray-700 Terkait (Opsional)</label>
                            <select name="audit_terkait" id="audit_terkait" class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                <option>Tidak ada</option>
                                <option>Audit Kepatuhan SOP Logistik Q3</option>
                                <option>Audit Sistem Informasi</option>
                            </select>
                        </div>

                        <!-- Tanggal Peristiwa -->
                        <div>
                            <label for="tanggal_peristiwa" class="block text-sm font-medium text-gray-700 Peristiwa</label>
                            <input type="date" name="tanggal_peristiwa" id="tanggal_peristiwa" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Jelaskan detail peristiwa atau catatan yang ingin ditambahkan."></textarea>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-end space-x-4">
                            <button type="button" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Simpan Catatan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md" x-data="{ items: [], newItem: { auditor: '{{ Auth::user()->name ?? \'Nama Auditor Otomatis\' }}', auditee: '', indicator: '', weight: '', criteria: '', document: '' } }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-700">Tambah Riwayat/Transaksi Audit</h1>
        <a href="{{ route('history') }}" class="text-sm text-emerald-600 hover:text-emerald-500 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Kembali ke Daftar Riwayat
        </a>
    </div>

    <!-- Form Tambah Item Transaksi -->
    <form @submit.prevent="items.push(JSON.parse(JSON.stringify(newItem))); newItem.auditee=''; newItem.indicator=''; newItem.weight=''; newItem.criteria=''; newItem.document=''" class="space-y-6 border-b border-gray-200 pb-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="auditor_name_history" class="block text-sm font-medium text-gray-700">Nama Auditor</label>
                <input type="text" id="auditor_name_history" x-model="newItem.auditor" readonly
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 sm:text-sm focus:outline-none">
            </div>
                <div>
                <label for="auditee_name_history" class="block text-sm font-medium text-gray-700">Nama Auditee</label>
                <input type="text" id="auditee_name_history" x-model="newItem.auditee" placeholder="Masukkan nama auditee"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
            </div>
        </div>
        
        <div>
            <label for="indicator_history" class="block text-sm font-medium text-gray-700">Indikator</label>
            <input type="text" id="indicator_history" x-model="newItem.indicator" placeholder="Indikator yang diaudit"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="weight_history" class="block text-sm font-medium text-gray-700">Bobot (%)</label>
                <input type="text" id="weight_history" x-model="newItem.weight" placeholder="Contoh: 15%"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
            </div>
            <div>
                <label for="criteria_history" class="block text-sm font-medium text-gray-700">Kriteria</label>
                <select id="criteria_history" x-model="newItem.criteria"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                    <option value="">Pilih Kriteria</option>
                    <option value="Kriteria A">Kriteria A (Contoh)</option>
                    <option value="Kriteria B">Kriteria B (Contoh)</option>
                    <option value="Kriteria C">Kriteria C (Contoh)</option>
                </select>
            </div>
            </div>

        <div>
            <label for="document_history" class="block text-sm font-medium text-gray-700">Dokumen Terkait (jika ada)</label>
            <select id="document_history" x-model="newItem.document"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                <option value="">Pilih Dokumen</option>
                <option value="Dokumen X.pdf">Dokumen X.pdf (Contoh)</option>
                <option value="Dokumen Y.docx">Dokumen Y.docx (Contoh)</option>
                <option value="-">Tidak ada dokumen khusus</option>
            </select>
    </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-4 py-2 rounded-md shadow-sm text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 -mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambahkan ke Transaksi Sesi Ini
            </button>
        </div>
    </form>

    <!-- Daftar Transaksi Sesi Ini -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Transaksi Sesi Ini (<span x-text="items.length"></span> item)</h2>
        <div class="overflow-x-auto bg-gray-50 p-4 rounded-md">
            <template x-if="items.length === 0">
                <p class="text-center text-gray-500 py-4">Belum ada item transaksi yang ditambahkan.</p>
            </template>
            <template x-if="items.length > 0">
            <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                    <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Auditee</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Indikator</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bobot</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dokumen</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700" x-text="item.auditee"></td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700" x-text="item.indicator"></td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700" x-text="item.weight"></td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700" x-text="item.criteria"></td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700" x-text="item.document"></td>
                                <td class="px-4 py-2 whitespace-nowrap text-right text-sm">
                                    <button @click="items.splice(index, 1)" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                        </td>
                    </tr>
                        </template>
                </tbody>
            </table>
            </template>
        </div>
    </div>

    <div class="mt-8 flex justify-end space-x-3">
        <a href="{{ route('history') }}" class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Batal & Kembali
        </a>
        <button type="button" @click="if(items.length > 0) { alert('Menyimpan ' + items.length + ' item transaksi history... (implementasi simpan ke db)'); items=[]; } else { alert('Tidak ada item untuk disimpan.'); }" 
                :disabled="items.length === 0"
                :class="{ 'opacity-50 cursor-not-allowed': items.length === 0 }"
                class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-2 rounded-md shadow-sm text-sm">
            Simpan Semua Transaksi Sesi Ini
        </button>
    </div>
</div>
@endsection 