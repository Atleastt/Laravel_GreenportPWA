<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Dokumen Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900
                    <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Nama Dokumen -->
                        <div>
                            <label for="nama_dokumen" class="block text-sm font-medium text-gray-700 Dokumen</label>
                            <input type="text" name="nama_dokumen" id="nama_dokumen" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: Laporan Audit Keuangan Q1">
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Jelaskan isi atau tujuan dari dokumen ini."></textarea>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700
                            <select name="kategori" id="kategori" class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                <option>Laporan Audit</option>
                                <option>Bukti Pendukung</option>
                                <option>Rencana Audit</option>
                                <option>Dokumen Kebijakan</option>
                            </select>
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label for="file_upload" class="block text-sm font-medium text-gray-700 File</label>
                            <div class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600
                                        <label for="file_upload" class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                                            <span>Unggah sebuah file</span>
                                            <input id="file_upload" name="file_upload" type="file" class="sr-only">
                                        </label>
                                        <p class="pl-1">atau tarik dan lepas</p>
                                    </div>
                                    <p class="text-xs text-gray-500 JPG, GIF, PDF hingga 10MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-end space-x-4">
                            <button type="button" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@section('content')
    <h1 class="text-2xl font-semibold text-gray-700 mb-4">Tambah Dokumen</h1>
    <p class="text-gray-600">Ini adalah placeholder untuk halaman Tambah Dokumen.</p>
    <p class="text-gray-600 mt-2">Konten spesifik dan form untuk menambah dokumen akan diimplementasikan di sini berdasarkan desain Figma.</p>
    
    <div class="mt-6 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Form Tambah Dokumen</h2>
        <form action="#" method="POST" class="space-y-4">
            <div>
                <label for="document_name" class="block text-sm font-medium text-gray-700">Nama Dokumen</label>
                <input type="text" name="document_name" id="document_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm" placeholder="Masukkan nama dokumen">
            </div>
            <div>
                <label for="document_category" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select id="document_category" name="document_category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                    <option>Pilih Kategori</option>
                    <option>Kategori A</option>
                    <option>Kategori B</option>
                </select>
            </div>
            <div>
                <label for="document_file" class="block text-sm font-medium text-gray-700">Upload File</label>
                <input type="file" name="document_file" id="document_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>
            <div class="flex justify-end">
                <button type="button" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 mr-2">Batal</button>
                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Simpan Dokumen</button>
            </div>
        </form>
    </div>
@endsection
