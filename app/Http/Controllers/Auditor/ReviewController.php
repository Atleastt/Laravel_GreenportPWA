<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\Criterion;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Store a new review or update an existing one for a specific audit criterion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Audit  $audit
     * @param  \App\Models\Criterion  $criterion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Audit $audit, Criterion $criterion): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'auditor_review_status' => ['required', 'string', 'in:Pending,Approved,RevisionNeeded'],
            'auditor_review_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validatedData = $validator->validated();

        // Update the pivot table
        $audit->criteria()->updateExistingPivot($criterion->id, [
            'auditor_review_status' => $validatedData['auditor_review_status'],
            'auditor_review_notes' => $validatedData['auditor_review_notes'],
        ]);

        return redirect()->route('auditor.audits.show', $audit->id)
            ->with('success', 'Review untuk kriteria berhasil disimpan.');
    }
}
