@extends('layouts.app')

@section('title', 'Tambah Rekomendasi')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Tambah Rekomendasi</h1>
            <p class="mt-2 text-sm text-gray-600">Buat rekomendasi perbaikan berdasarkan temuan audit</p>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('rekomendasi.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Audit Selection -->
                        <div>
                            <label for="audit_id" class="block text-sm font-medium text-gray-700">Audit</label>
                            <select name="audit_id" id="audit_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" {{ isset($audit) ? 'readonly' : '' }}>
                                @if(isset($audit))
                                    <option value="{{ $audit->id }}" selected>{{ $audit->nama_audit ?? $audit->nama ?? 'Audit #'.$audit->id }}</option>
                                @else
                                    <option value="">Pilih Audit</option>
                                    @foreach($audits as $auditOption)
                                        <option value="{{ $auditOption->id }}">{{ $auditOption->nama_audit ?? $auditOption->nama ?? 'Audit #'.$auditOption->id }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('audit_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori Temuan</label>
                            <select name="kategori" id="kategori" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Pilih Kategori</option>
                                <option value="Mayor" {{ old('kategori') == 'Mayor' ? 'selected' : '' }}>Mayor</option>
                                <option value="Minor" {{ old('kategori') == 'Minor' ? 'selected' : '' }}>Minor</option>
                                <option value="Observasi" {{ old('kategori') == 'Observasi' ? 'selected' : '' }}>Observasi</option>
                            </select>
                            @error('kategori')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi Temuan -->
                        <div>
                            <label for="deskripsi_temuan" class="block text-sm font-medium text-gray-700">Deskripsi Temuan</label>
                            <textarea name="deskripsi_temuan" id="deskripsi_temuan" rows="4" required 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Jelaskan temuan secara detail...">{{ old('deskripsi_temuan') }}</textarea>
                            @error('deskripsi_temuan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rekomendasi Perbaikan -->
                        <div>
                            <label for="rekomendasi_perbaikan" class="block text-sm font-medium text-gray-700">Rekomendasi Perbaikan</label>
                            <textarea name="rekomendasi_perbaikan" id="rekomendasi_perbaikan" rows="4" required 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Jelaskan rekomendasi perbaikan yang disarankan...">{{ old('rekomendasi_perbaikan') }}</textarea>
                            @error('rekomendasi_perbaikan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prioritas dan Batas Waktu -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="prioritas" class="block text-sm font-medium text-gray-700">Prioritas</label>
                                <select name="prioritas" id="prioritas" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Pilih Prioritas</option>
                                    <option value="Tinggi" {{ old('prioritas') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                                    <option value="Sedang" {{ old('prioritas') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                    <option value="Rendah" {{ old('prioritas') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                                </select>
                                @error('prioritas')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="batas_waktu" class="block text-sm font-medium text-gray-700">Batas Waktu</label>
                                <input type="date" name="batas_waktu" id="batas_waktu" value="{{ old('batas_waktu') }}" required 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('batas_waktu')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('pages.rekomendasi_auditor') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan Rekomendasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('batas_waktu').setAttribute('min', today);
    });
</script>
@endpush
