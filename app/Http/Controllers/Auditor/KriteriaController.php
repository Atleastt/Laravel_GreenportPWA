<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response|View
    {
        $kriterias = Kriteria::latest()->paginate(10);

        // Jika view auditor.kriteria.index tersedia maka render, jika tidak kembalikan JSON
        if (view()->exists('auditor.kriteria.index')) {
            return view('auditor.kriteria.index', compact('kriterias'));
        }

        return response()->json($kriterias);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response|View
    {
        if (view()->exists('auditor.kriteria.create')) {
            return view('auditor.kriteria.create');
        }

        return response()->noContent();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|Response
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255|unique:kriterias',
            'deskripsi_kriteria' => 'required|string',
        ]);

        Kriteria::create($validated);

        return $this->responseSuccess('Kriteria berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kriteria $kriterium): Response|View
    {
        if (view()->exists('auditor.kriteria.show')) {
            return view('auditor.kriteria.show', ['kriteria' => $kriterium]);
        }

        return response()->json($kriterium);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kriteria $kriterium): Response|View
    {
        if (view()->exists('auditor.kriteria.edit')) {
            return view('auditor.kriteria.edit', ['kriteria' => $kriterium]);
        }

        return response()->noContent();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kriteria $kriterium): RedirectResponse|Response
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255|unique:kriterias,nama_kriteria,' . $kriterium->id,
            'deskripsi_kriteria' => 'required|string',
        ]);

        $kriterium->update($validated);

        return $this->responseSuccess('Kriteria berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kriteria $kriterium): RedirectResponse|Response
    {
        $kriterium->delete();

        return $this->responseSuccess('Kriteria berhasil dihapus.');
    }

    private function responseSuccess(string $message): RedirectResponse|Response
    {
        if (url()->previous()) {
            return redirect()->back()->with('success', $message);
        }
        return response()->json(['message' => $message]);
    }
}
