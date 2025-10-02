<?php

namespace App\Http\Controllers\Auditee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audit;
use Illuminate\Support\Facades\Storage;

class ChecklistController extends Controller
{
    /**
     * Show self-assessment checklist form for auditee.
     */
    public function show(Audit $audit)
    {
        // Hanya auditee yang ditugaskan yang boleh mengisi checklist
        if (\Illuminate\Support\Facades\Auth::id() !== $audit->auditee_id) {
            abort(403, 'Anda tidak berwenang mengakses halaman ini.');
        }
        $audit->load('criteria');
        return view('auditee.checklist.form', compact('audit'));
    }

    /**
     * Store self-assessment answers for auditee.
     */
    public function store(Request $request, Audit $audit)
    {
        // Hanya auditee yang ditugaskan yang boleh menyimpan checklist
        if (\Illuminate\Support\Facades\Auth::id() !== $audit->auditee_id) {
            abort(403, 'Anda tidak berwenang mengakses halaman ini.');
        }
        $audit->load('criteria');
        $rules = [];
        foreach ($audit->criteria as $criterion) {
            $rules["items.{$criterion->id}.status"] = 'required|in:Open,InProgress,Closed';
            $rules["items.{$criterion->id}.auditee_notes"] = 'nullable|string';
            $rules["items.{$criterion->id}.attachment"] = 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240';
        }
        $validated = $request->validate($rules);

        foreach ($validated['items'] as $criterionId => $dataItem) {
            $pivotData = [
                'status' => $dataItem['status'],
                'auditee_notes' => $dataItem['auditee_notes'] ?? null,
            ];
            if (!empty($dataItem['attachment'])) {
                $path = $dataItem['attachment']->store('audit_evidence', 'public');
                $pivotData['auditee_attachment_path'] = $path;
            }
            $audit->criteria()->updateExistingPivot($criterionId, $pivotData);
        }

        return redirect()->route('auditee.tugas.show', $audit)->with('success', 'Checklist berhasil disimpan.');
    }
}
