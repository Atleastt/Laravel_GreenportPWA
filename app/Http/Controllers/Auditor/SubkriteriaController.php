<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SubkriteriaController extends Controller
{
    public function index(): Response|View
    {
        $subkriterias = Subkriteria::with('kriteria')->latest()->paginate(10);
        return view()->exists('auditor.subkriteria.index')
            ? view('auditor.subkriteria.index', compact('subkriterias'))
            : response()->json($subkriterias);
    }

    public function create(): Response|View
    {
        $kriterias = Kriteria::all();
        return view()->exists('auditor.subkriteria.create')
            ? view('auditor.subkriteria.create', compact('kriterias'))
            : response()->noContent();
    }

    public function store(Request $request): RedirectResponse|Response
    {
        $validated = $request->validate([
            'kriteria_id' => 'required|exists:kriterias,id',
            'nama_subkriteria' => 'required|string|max:255',
            'deskripsi_subkriteria' => 'required|string',
        ]);
        Subkriteria::create($validated);
        return $this->responseSuccess('Subkriteria berhasil ditambahkan.');
    }

    public function show(Subkriteria $subkriterium): Response|View
    {
        return view()->exists('auditor.subkriteria.show')
            ? view('auditor.subkriteria.show', ['subkriteria' => $subkriterium->load('kriteria')])
            : response()->json($subkriterium);
    }

    public function edit(Subkriteria $subkriterium): Response|View
    {
        $kriterias = Kriteria::all();
        return view()->exists('auditor.subkriteria.edit')
            ? view('auditor.subkriteria.edit', compact('subkriterium', 'kriterias'))
            : response()->noContent();
    }

    public function update(Request $request, Subkriteria $subkriterium): RedirectResponse|Response
    {
        $validated = $request->validate([
            'kriteria_id' => 'required|exists:kriterias,id',
            'nama_subkriteria' => 'required|string|max:255',
            'deskripsi_subkriteria' => 'required|string',
        ]);
        $subkriterium->update($validated);
        return $this->responseSuccess('Subkriteria berhasil diperbarui.');
    }

    public function destroy(Subkriteria $subkriterium): RedirectResponse|Response
    {
        $subkriterium->delete();
        return $this->responseSuccess('Subkriteria berhasil dihapus.');
    }

    private function responseSuccess(string $message): RedirectResponse|Response
    {
        return url()->previous() ? redirect()->back()->with('success', $message) : response()->json(['message' => $message]);
    }
}
