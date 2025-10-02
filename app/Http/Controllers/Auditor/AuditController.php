<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function index(): Response|View
    {
        $audits = Audit::with(['auditor', 'auditee'])->latest()->paginate(10);
        return view('pages.daftar_audit_auditor', compact('audits'));
    }

    public function create(): Response|View
    {
        $auditors = User::role('Auditor')->get();
        $auditees = User::role('Auditee')->get();
        return view()->exists('auditor.audit.create') ? view('auditor.audit.create', compact('auditors', 'auditees')) : response()->noContent();
    }

    public function store(Request $request): RedirectResponse|Response
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'auditor_id' => 'required|exists:users,id',
            'auditee_id' => 'required|exists:users,id',
            'tanggal_jadwal' => 'required|date',
        ]);

        $validated['status'] = 'scheduled';
        $audit = Audit::create($validated);

        return $this->responseSuccess('Audit berhasil dibuat.', $audit);
    }

    public function show(Audit $audit): Response|View
    {
        $audit->load(['criteria.subkriteria.kriteria', 'auditor', 'auditee']);
        return view()->exists('auditor.audit.show') ? view('auditor.audit.show', compact('audit')) : response()->json($audit);
    }

    public function edit(Audit $audit): Response|View
    {
        $auditors = User::role('Auditor')->get();
        $auditees = User::role('Auditee')->get();
        return view()->exists('auditor.audit.edit') ? view('auditor.audit.edit', compact('audit', 'auditors', 'auditees')) : response()->noContent();
    }

    public function update(Request $request, Audit $audit): RedirectResponse|Response
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'auditor_id' => 'required|exists:users,id',
            'auditee_id' => 'required|exists:users,id',
            'tanggal_jadwal' => 'required|date',
            'status' => 'nullable|in:scheduled,ongoing,completed,cancelled',
        ]);

        $audit->update($validated);
        return $this->responseSuccess('Audit berhasil diperbarui.', $audit);
    }

    public function destroy(Audit $audit): RedirectResponse|Response
    {
        $audit->delete();
        return $this->responseSuccess('Audit berhasil dihapus.');
    }

    private function responseSuccess(string $message, $data = null): RedirectResponse|Response
    {
        return url()->previous() ? redirect()->back()->with('success', $message) : response()->json(['message' => $message, 'data' => $data]);
    }
}