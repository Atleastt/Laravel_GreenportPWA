<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\Indikator;
use App\Models\Subkriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class IndikatorController extends Controller
{
    public function index(): Response|View
    {
        $indikators = Indikator::with('subkriteria.kriteria')->latest()->paginate(10);
        return view()->exists('auditor.indikator.index')
            ? view('auditor.indikator.index', compact('indikators'))
            : response()->json($indikators);
    }

    public function create(): Response|View
    {
        $subkriterias = Subkriteria::with('kriteria')->get();
        return view()->exists('auditor.indikator.create')
            ? view('auditor.indikator.create', compact('subkriterias'))
            : response()->noContent();
    }

    public function store(Request $request): RedirectResponse|Response
    {
        $validated = $request->validate([
            'subkriteria_id' => 'required|exists:subkriterias,id',
            'teks_indikator' => 'required|string',
            'bobot' => 'required|numeric|min:0',
            'tipe_jawaban' => 'required|in:teks,skala,ya_tidak',
        ]);

        Indikator::create($validated);

        return $this->responseSuccess('Indikator berhasil ditambahkan.');
    }

    public function show(Indikator $indikator): Response|View
    {
        $indikator->load('subkriteria.kriteria');
        return view()->exists('auditor.indikator.show')
            ? view('auditor.indikator.show', compact('indikator'))
            : response()->json($indikator);
    }

    public function edit(Indikator $indikator): Response|View
    {
        $subkriterias = Subkriteria::with('kriteria')->get();
        return view()->exists('auditor.indikator.edit')
            ? view('auditor.indikator.edit', compact('indikator', 'subkriterias'))
            : response()->noContent();
    }

    public function update(Request $request, Indikator $indikator): RedirectResponse|Response
    {
        $validated = $request->validate([
            'subkriteria_id' => 'required|exists:subkriterias,id',
            'teks_indikator' => 'required|string',
            'bobot' => 'required|numeric|min:0',
            'tipe_jawaban' => 'required|in:teks,skala,ya_tidak',
        ]);

        $indikator->update($validated);

        return $this->responseSuccess('Indikator berhasil diperbarui.');
    }

    public function destroy(Indikator $indikator): RedirectResponse|Response
    {
        $indikator->delete();
        return $this->responseSuccess('Indikator berhasil dihapus.');
    }

    private function responseSuccess(string $message): RedirectResponse|Response
    {
        return url()->previous() ? redirect()->back()->with('success', $message) : response()->json(['message' => $message]);
    }
}
