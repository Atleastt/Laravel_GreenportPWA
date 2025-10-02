<?php

namespace App\Http\Controllers;

use App\Models\TindakLanjut;
use App\Models\Rekomendasi;
use App\Models\Audit;
use App\Notifications\AuditNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TindakLanjutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('Auditor')) {
            // Auditor sees all follow-ups for their audits
            $tindakLanjuts = TindakLanjut::with(['rekomendasi.audit', 'auditee'])
                                       ->whereHas('rekomendasi', function($query) {
                                           $query->where('auditor_id', Auth::id());
                                       })
                                       ->latest()
                                       ->paginate(10);
        } else {
            // Auditee sees only their follow-ups
            $tindakLanjuts = TindakLanjut::with(['rekomendasi.audit'])
                                       ->where('auditee_id', Auth::id())
                                       ->latest()
                                       ->paginate(10);
        }

        return view('pages.tindak_lanjut.index', compact('tindakLanjuts'));
    }

    public function create(Rekomendasi $rekomendasi)
    {
        // Only auditee can create follow-up
        if (Auth::id() !== $rekomendasi->audit->auditee_id) {
            abort(403, 'Anda tidak berwenang membuat tindak lanjut untuk rekomendasi ini');
        }

        return view('pages.tindak_lanjut.create', compact('rekomendasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rekomendasi_id' => 'required|exists:rekomendasis,id',
            'rencana_tindakan' => 'required|string',
            'target_penyelesaian' => 'required|date|after:today',
            'penanggung_jawab' => 'required|string|max:255',
            'sumber_daya_dibutuhkan' => 'nullable|string',
            'milestone.*' => 'required|string',
            'tanggal_milestone.*' => 'required|date'
        ]);

        $rekomendasi = Rekomendasi::findOrFail($request->rekomendasi_id);
        
        // Check authorization
        if (Auth::id() !== $rekomendasi->audit->auditee_id) {
            abort(403, 'Unauthorized');
        }

        $tindakLanjut = TindakLanjut::create([
            'rekomendasi_id' => $request->rekomendasi_id,
            'auditee_id' => Auth::id(),
            'rencana_tindakan' => $request->rencana_tindakan,
            'target_penyelesaian' => $request->target_penyelesaian,
            'penanggung_jawab' => $request->penanggung_jawab,
            'sumber_daya_dibutuhkan' => $request->sumber_daya_dibutuhkan,
            'status_progres' => 'Direncanakan'
        ]);

        // Save milestones
        if ($request->milestone && $request->tanggal_milestone) {
            $milestones = [];
            foreach ($request->milestone as $index => $milestone) {
                $milestones[] = [
                    'milestone' => $milestone,
                    'tanggal_target' => $request->tanggal_milestone[$index],
                    'status' => 'Belum Dimulai'
                ];
            }
            $tindakLanjut->update(['milestones' => json_encode($milestones)]);
        }

        // Update rekomendasi status
        $rekomendasi->update(['status' => 'Sedang Berjalan']);

        // Notify auditor
        $auditor = $rekomendasi->auditor;
        $auditor->notify(new AuditNotification(
            "Tindak lanjut baru dibuat untuk rekomendasi: {$rekomendasi->deskripsi_temuan}",
            $rekomendasi->audit_id,
            'follow_up'
        ));

        return redirect()->route('tindak-lanjut.index')
                         ->with('success', 'Rencana tindak lanjut berhasil dibuat');
    }

    public function show(TindakLanjut $tindakLanjut)
    {
        $tindakLanjut->load(['rekomendasi.audit', 'auditee']);
        return view('pages.tindak_lanjut.show', compact('tindakLanjut'));
    }

    public function updateProgress(Request $request, TindakLanjut $tindakLanjut)
    {
        $request->validate([
            'status_progres' => 'required|in:Direncanakan,Sedang Berjalan,Hampir Selesai,Selesai,Terhambat',
            'persentase_penyelesaian' => 'required|integer|min:0|max:100',
            'catatan_progres' => 'nullable|string',
            'bukti_penyelesaian' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240'
        ]);

        // Check authorization
        if (Auth::id() !== $tindakLanjut->auditee_id) {
            abort(403, 'Unauthorized');
        }

        $updateData = [
            'status_progres' => $request->status_progres,
            'persentase_penyelesaian' => $request->persentase_penyelesaian,
            'catatan_progres' => $request->catatan_progres,
            'tanggal_update_terakhir' => now()
        ];

        // Handle file upload
        if ($request->hasFile('bukti_penyelesaian')) {
            $file = $request->file('bukti_penyelesaian');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tindak_lanjut', $filename, 'public');
            $updateData['bukti_penyelesaian'] = $path;
        }

        $tindakLanjut->update($updateData);

        // Update rekomendasi status if completed
        if ($request->status_progres === 'Selesai') {
            $tindakLanjut->rekomendasi->update(['status' => 'Selesai']);
        }

        // Notify auditor
        $auditor = $tindakLanjut->rekomendasi->auditor;
        $auditor->notify(new AuditNotification(
            "Progress tindak lanjut diperbarui: {$request->status_progres} ({$request->persentase_penyelesaian}%)",
            $tindakLanjut->rekomendasi->audit_id,
            'progress_update'
        ));

        return redirect()->back()->with('success', 'Progress berhasil diperbarui');
    }

    public function updateMilestone(Request $request, TindakLanjut $tindakLanjut, $milestoneIndex)
    {
        $request->validate([
            'status' => 'required|in:Belum Dimulai,Sedang Berjalan,Selesai,Terlambat'
        ]);

        // Check authorization
        if (Auth::id() !== $tindakLanjut->auditee_id) {
            abort(403, 'Unauthorized');
        }

        $milestones = json_decode($tindakLanjut->milestones, true) ?? [];
        
        if (isset($milestones[$milestoneIndex])) {
            $milestones[$milestoneIndex]['status'] = $request->status;
            $milestones[$milestoneIndex]['tanggal_selesai'] = $request->status === 'Selesai' ? now()->toDateString() : null;
            
            $tindakLanjut->update(['milestones' => json_encode($milestones)]);
        }

        return redirect()->back()->with('success', 'Status milestone berhasil diperbarui');
    }
}
