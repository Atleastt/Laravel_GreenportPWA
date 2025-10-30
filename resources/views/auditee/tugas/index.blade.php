<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Daftar Tugas Audit Anda</h2>

                    {{-- 🔘 Kontrol Simulasi Jaringan --}}
                    <div class="mb-4 flex space-x-2">
                        <button onclick="setNetworkMode('stable')" class="px-3 py-1 bg-green-500 text-white rounded">Stable</button>
                        <button onclick="setNetworkMode('intermittent')" class="px-3 py-1 bg-yellow-500 text-white rounded">Intermittent</button>
                        <button onclick="setNetworkMode('offline')" class="px-3 py-1 bg-red-500 text-white rounded">Offline</button>
                    </div>

                    {{-- 📦 Form Upload Bukti (contoh) --}}
                    <form id="uploadForm" class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Upload Bukti</label>
                        <input type="file" name="file" id="fileInput" class="mb-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded">Upload</button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Audit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auditor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($audits as $audit)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $audit->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $audit->auditor->full_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($audit->scheduled_start_date)->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('auditee.tugas.checklist', $audit) }}" class="text-indigo-600 hover:text-indigo-900">Isi Checklist</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-gray-50">
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                            Anda belum memiliki tugas audit.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $audits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Script PWA & Offline Upload --}}
    <script>
        // --- REGISTER SERVICE WORKER ---
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(reg => console.log('✅ Service Worker registered:', reg.scope))
                    .catch(err => console.error('❌ SW registration failed:', err));
            });
        }

        // --- NETWORK SIMULATION ---
        function setNetworkMode(mode) {
            navigator.serviceWorker.controller?.postMessage({
                type: mode === 'offline' ? 'ENABLE_OFFLINE' :
                      mode === 'intermittent' ? 'ENABLE_INTERMITTENT' : 'RESET_NETWORK',
                config: { bandwidth: '300kbps', disconnectInterval: 5000 }
            });
            console.log(`🌐 Network mode: ${mode}`);
        }

        // --- UPLOAD FORM HANDLER ---
        const uploadForm = document.getElementById('uploadForm');
        uploadForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fileInput = document.getElementById('fileInput');
            if (!fileInput.files.length) return alert('Pilih file dulu!');

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);

            try {
                const response = await fetch('/api/upload-bukti', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Test-Mode': 'true',
                        'X-Test-ID': crypto.randomUUID()
                    }
                });
                const data = await response.json();
                console.log('📤 Upload result:', data);
            } catch (err) {
                console.error('❌ Upload failed:', err);
            }
        });
    </script>
</x-app-layout>
