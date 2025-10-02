@extends('layouts.app')

@section('title', 'Tindak Lanjut')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">
                    Tindak Lanjut Rekomendasi
                </h2>
                
                @hasrole('Auditor')
                <a href="{{ route('tindak-lanjut.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Tindak Lanjut
                </a>
                @endhasrole
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-600">Total Tindak Lanjut</p>
                            <p class="text-lg font-semibold text-blue-900">{{ $statistics['total'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-600">Dalam Proses</p>
                            <p class="text-lg font-semibold text-yellow-900">{{ $statistics['in_progress'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-600">Selesai</p>
                            <p class="text-lg font-semibold text-green-900">{{ $statistics['completed'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-600">Terlambat</p>
                            <p class="text-lg font-semibold text-red-900">{{ $statistics['overdue'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <form method="GET" action="{{ route('tindak-lanjut.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-0">
                        <label for="rekomendasi_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Rekomendasi</label>
                        <select name="rekomendasi_id" id="rekomendasi_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Rekomendasi</option>
                            @foreach($rekomendasis ?? [] as $rekomendasi)
                                <option value="{{ $rekomendasi->id }}" {{ request('rekomendasi_id') == $rekomendasi->id ? 'selected' : '' }}>
                                    {{ Str::limit($rekomendasi->rekomendasi, 50) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-0">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="Planned" {{ request('status') == 'Planned' ? 'selected' : '' }}>Planned</option>
                            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-150">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tindak Lanjut Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rekomendasi
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Deskripsi Tindakan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Progress
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Target Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                PIC
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tindakLanjuts ?? [] as $tindakLanjut)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs">
                                        <p class="truncate font-medium" title="{{ $tindakLanjut->rekomendasi->rekomendasi }}">
                                            {{ Str::limit($tindakLanjut->rekomendasi->rekomendasi, 80) }}
                                        </p>
                                        <p class="text-xs text-gray-500">Priority: {{ $tindakLanjut->rekomendasi->prioritas }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs truncate" title="{{ $tindakLanjut->action_description }}">
                                        {{ Str::limit($tindakLanjut->action_description, 100) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $tindakLanjut->progress_percentage }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">{{ $tindakLanjut->progress_percentage }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $tindakLanjut->getStatusColor() }}">
                                        {{ $tindakLanjut->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        {{ $tindakLanjut->target_date ? $tindakLanjut->target_date->format('d/m/Y') : 'N/A' }}
                                        @if($tindakLanjut->target_date && $tindakLanjut->target_date->isPast() && $tindakLanjut->status !== 'Completed')
                                            <span class="text-red-500 text-xs block">Terlambat</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $tindakLanjut->responsible_person->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('tindak-lanjut.show', $tindakLanjut->id) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        @hasanyrole('Auditor|Auditee')
                                        <a href="{{ route('tindak-lanjut.edit', $tindakLanjut->id) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        @endhasanyrole

                                        @if($tindakLanjut->status !== 'Completed')
                                        <!-- Update Progress Button -->
                                        <button onclick="showProgressModal({{ $tindakLanjut->id }}, {{ $tindakLanjut->progress_percentage }})" 
                                                class="text-green-600 hover:text-green-900" title="Update Progress">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-8 8"/>
                                            </svg>
                                        </button>
                                        @endif

                                        @hasrole('Auditor')
                                        <form method="POST" action="{{ route('tindak-lanjut.destroy', $tindakLanjut->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900" 
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this follow-up action?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                        @endhasrole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada tindak lanjut ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($tindakLanjuts) && $tindakLanjuts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-6">
                    {{ $tindakLanjuts->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

<!-- Progress Update Modal -->
<div id="progressModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Progress</h3>
            <form id="progressForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="progress_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                        Progress Percentage
                    </label>
                    <input type="range" 
                           id="progress_percentage" 
                           name="progress_percentage" 
                           min="0" 
                           max="100" 
                           class="w-full" 
                           oninput="document.getElementById('progressValue').textContent = this.value + '%'">
                    <div class="text-center mt-2">
                        <span id="progressValue" class="text-lg font-semibold text-blue-600">0%</span>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="progress_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Progress Notes (Optional)
                    </label>
                    <textarea id="progress_notes" 
                              name="progress_notes" 
                              rows="3" 
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Add any notes about the progress..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="hideProgressModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-150">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150">
                        Update Progress
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showProgressModal(tindakLanjutId, currentProgress) {
    document.getElementById('progressModal').classList.remove('hidden');
    document.getElementById('progressForm').action = '/tindak-lanjut/' + tindakLanjutId + '/update-progress';
    document.getElementById('progress_percentage').value = currentProgress;
    document.getElementById('progressValue').textContent = currentProgress + '%';
    document.getElementById('progress_notes').value = '';
}

function hideProgressModal() {
    document.getElementById('progressModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('progressModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideProgressModal();
    }
});
</script>
@endsection
