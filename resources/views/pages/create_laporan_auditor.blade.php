<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li>
                            <a href="{{ route('daftar.audit.auditor') }}" class="text-gray-500 hover:text-gray-700">Daftar Audit</a>
                        </li>
                        <li>
                            <svg class="flex-shrink-0 h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </li>
                        <li class="text-gray-900 font-medium">Buat Laporan</li>
                    </ol>
                </nav>
                <h2 class="mt-2 font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Buat Laporan Audit untuk: ') }} {{ $audit->title }}
                </h2>
            </div>
            <a href="{{ route('daftar.audit.auditor') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-md shadow-sm text-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('pelaporan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="audit_id" value="{{ $audit->id }}">

                        <!-- Judul Laporan -->
                        <div class="mb-4">
                            <label for="report_title" class="block text-sm font-medium text-gray-700">Judul Laporan</label>
                            <input type="text" id="report_title" name="report_title" value="{{ old('report_title', 'Laporan Audit ' . $audit->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <!-- Ringkasan Eksekutif -->
                        <div class="mb-4">
                            <label for="executive_summary" class="block text-sm font-medium text-gray-700">Ringkasan Eksekutif</label>
                            <textarea id="executive_summary" name="executive_summary" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('executive_summary') }}</textarea>
                        </div>

                        <!-- Temuan dan Rekomendasi -->
                        <div class="mb-4">
                            <label for="findings_recommendations" class="block text-sm font-medium text-gray-700">Temuan dan Rekomendasi</label>
                            <textarea id="findings_recommendations" name="findings_recommendations" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('findings_recommendations') }}</textarea>
                        </div>

                        <!-- Skor Kepatuhan -->
                        <div class="mb-4">
                            <label for="compliance_score" class="block text-sm font-medium text-gray-700">Skor Kepatuhan (%)</label>
                            <input type="number" id="compliance_score" name="compliance_score" value="{{ old('compliance_score') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="0" max="100" step="0.01">
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('daftar.audit.auditor') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-md shadow-sm text-sm">
                               Batal
                            </a>
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-2 rounded-md shadow-sm">
                               Simpan Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
