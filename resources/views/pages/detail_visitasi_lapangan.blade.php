<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Detail Visitasi Lapangan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <strong>Tanggal Visitasi:</strong>
                        {{ \Carbon\Carbon::parse($visitasi->tanggal_visitasi)->isoFormat('dddd, D MMMM YYYY') }}
                    </div>
                    <div>
                        <strong>Auditor:</strong>
                        {{ $visitasi->auditor_name }}
                    </div>
                    <div>
                        <strong>Auditee:</strong>
                        {{ $visitasi->auditee_name }}
                    </div>
                    <div>
                        <strong>Status:</strong>
                        <span class="px-2 py-1 rounded-full {{ $visitasi->status == 'Selesai' ? 'bg-green-100 text-green-800' : ($visitasi->status == 'Dibatalkan' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $visitasi->status }}
                        </span>
                    </div>
                </div>
                @if($visitasi->catatan)
                    <div class="mt-4">
                        <strong>Catatan:</strong>
                        <p>{{ $visitasi->catatan }}</p>
                    </div>
                @endif
                <div class="mt-6">
                    <a href="{{ route('visitasi.lapangan') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
