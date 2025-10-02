<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Audit: ') }} {{ $audit->title }}
            </h2>
            <a href="{{ route('daftar.audit.auditor') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                &larr; Kembali ke Daftar Audit
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 Umum</h3>
                            <dl class="mt-2 border-t border-b border-gray-200 divide-y divide-gray-200
                                <div class="py-3 grid grid-cols-3 gap-4">
                                    <dt class="text-sm font-medium text-gray-500 Audit</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $audit->title }}</dd>
                                </div>
                                <div class="py-3 grid grid-cols-3 gap-4">
                                    <dt class="text-sm font-medium text-gray-500
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $audit->auditor->full_name ?? 'N/A' }}</dd>
                                </div>
                                <div class="py-3 grid grid-cols-3 gap-4">
                                    <dt class="text-sm font-medium text-gray-500
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $audit->auditee->full_name ?? 'N/A' }}</dd>
                                </div>
                                <div class="py-3 grid grid-cols-3 gap-4">
                                    <dt class="text-sm font-medium text-gray-500 Mulai</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ \Carbon\Carbon::parse($audit->scheduled_start_date)->translatedFormat('d F Y') }}</dd>
                                </div>
                                <div class="py-3 grid grid-cols-3 gap-4">
                                    <dt class="text-sm font-medium text-gray-500 Selesai</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ \Carbon\Carbon::parse($audit->scheduled_end_date)->translatedFormat('d F Y') }}</dd>
                                </div>
                                <div class="py-3 grid grid-cols-3 gap-4">
                                    <dt class="text-sm font-medium text-gray-500
                                    <dd class="text-sm text-gray-900 col-span-2">
                                        @php
                                            $statusClass = match($audit->status) {
                                                'Scheduled' => 'bg-gray-100 text-gray-800',
                                                'InProgress' => 'bg-blue-100 text-blue-800',
                                                'Completed' => 'bg-green-100 text-green-800',
                                                'Revising' => 'bg-yellow-100 text-yellow-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $audit->status }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('audits.edit', $audit) }}" class="rounded-md border border-transparent bg-yellow-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-yellow-600">Edit</a>
                        <form action="{{ route('audits.destroy', $audit) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus audit ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
