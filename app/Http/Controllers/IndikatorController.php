<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use App\Models\Subkriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndikatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $indikators = Indikator::with('subkriteria.kriteria')->latest()->paginate(10);
        return view('pages.indikator.index', compact('indikators'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $subkriterias = Subkriteria::with('kriteria')->get();
        return view('pages.indikator.create', compact('subkriterias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subkriteria_id' => 'required|exists:subkriterias,id',
            'teks_indikator' => 'required|string',
            'bobot' => 'required|numeric|min:0',
        ]);

        Indikator::create($validated);

        return redirect()->route('dashboard')->with('success', 'Indikator berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Indikator $indikator): View
    {
        $indikator->load('subkriteria.kriteria');
        return view('pages.indikator.show', compact('indikator'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Indikator $indikator): View
    {
        $subkriterias = Subkriteria::with('kriteria')->get();
        return view('pages.indikator.edit', compact('indikator', 'subkriterias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Indikator $indikator): RedirectResponse
    {
        $validated = $request->validate([
            'subkriteria_id' => 'required|exists:subkriterias,id',
            'teks_indikator' => 'required|string',
            'bobot' => 'required|numeric|min:0',
        ]);

        $indikator->update($validated);

        return redirect()->route('dashboard')->with('success', 'Indikator berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Indikator $indikator): RedirectResponse
    {
        $indikator->delete();

        return redirect()->route('dashboard')->with('success', 'Indikator berhasil dihapus.');
    }
}
