<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Jadwal Audit Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('audits.store') }}" method="POST" class="space-y-6">
                        @csrf
                        @if ($errors->any())
                            <div class="mb-4 rounded-md bg-red-50 p-4">
                                <ul class="list-disc list-inside text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Nama Audit -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Nama Audit</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('title') border-red-500 @enderror" placeholder="Contoh: Audit Kepatuhan SOP Logistik Q3" required>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Auditee -->
                        <div>
                            <label for="auditee_id" class="block text-sm font-medium text-gray-700">Pilih Auditee</label>
                            <select name="auditee_id" id="auditee_id" class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('auditee_id') border-red-500 @enderror" required>
                                <option value="">Pilih Auditee</option>
                                @foreach ($auditees as $auditee)
                                    <option value="{{ $auditee->id }}" {{ old('auditee_id') == $auditee->id ? 'selected' : '' }}>{{ $auditee->full_name }}</option>
                                @endforeach
                            </select>
                            @error('auditee_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <!-- Tanggal Mulai -->
                            <div>
                                <label for="scheduled_start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="date" name="scheduled_start_date" id="scheduled_start_date" value="{{ old('scheduled_start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('scheduled_start_date') border-red-500 @enderror" required>
                                @error('scheduled_start_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Selesai -->
                            <div>
                                <label for="scheduled_end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                <input type="date" name="scheduled_end_date" id="scheduled_end_date" value="{{ old('scheduled_end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('scheduled_end_date') border-red-500 @enderror" required>
                                @error('scheduled_end_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('daftar.audit.auditor') }}" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Batal</a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Simpan Jadwal Audit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>