<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChecklistTemplate;

class ChecklistTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = ChecklistTemplate::where('pembuat_auditor_id', Auth::id())
                          ->latest()
                          ->paginate(10);
        return view('auditor.checklist-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auditor.checklist-templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_template' => 'required|string|max:255',
            'deskripsi_template' => 'required|string',
        ]);
        $template = new ChecklistTemplate($validated);
        $template->pembuat_auditor_id = Auth::id();
        $template->save();
        return redirect()->route('auditor.checklist-templates.index')
                         ->with('success', 'Template berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ChecklistTemplate $checklistTemplate)
    {
        return view('auditor.checklist-templates.show', compact('checklistTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChecklistTemplate $checklistTemplate)
    {
        return view('auditor.checklist-templates.edit', compact('checklistTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChecklistTemplate $checklistTemplate)
    {
        $validated = $request->validate([
            'nama_template' => 'required|string|max:255',
            'deskripsi_template' => 'required|string',
        ]);
        $checklistTemplate->update($validated);
        return redirect()->route('auditor.checklist-templates.index')
                         ->with('success', 'Template berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChecklistTemplate $checklistTemplate)
    {
        $checklistTemplate->delete();
        return redirect()->route('auditor.checklist-templates.index')
                         ->with('success', 'Template berhasil dihapus.');
    }
}
