<?php

namespace App\Http\Controllers;

use App\Models\IndikatorDokumen;
use Illuminate\Http\Request;

class IndikatorDokumenController extends Controller
{
    public function index()
    {
        $indikatorDokumens = \App\Models\IndikatorDokumen::latest()->paginate(10);
        return view('pages.indikator_dokumen', compact('indikatorDokumens'));
    }

    public function create()
    {
        // Redirect to index, form is on the same page
        return redirect()->route('indikator-dokumen.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_indikator' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string|max:255',
        ]);

        \App\Models\IndikatorDokumen::create([
            'nama_indikator' => $validatedData['nama_indikator'],
            'deskripsi' => $validatedData['deskripsi'],
            'kategori' => $validatedData['kategori'],
        ]);

        return redirect()->route('indikator-dokumen.index')->with('success', 'Indikator berhasil ditambahkan.');
    }

    public function show(IndikatorDokumen $indikatorDokumen)
    {
        // Not used for now
    }

    public function edit(IndikatorDokumen $indikatorDokumen)
    {
        // Not used for now
    }

    public function update(Request $request, IndikatorDokumen $indikatorDokumen)
    {
        // Not used for now
    }

    public function destroy(IndikatorDokumen $indikatorDokumen)
    {
        $indikatorDokumen->delete();
        return redirect()->route('indikator-dokumen.index')->with('success', 'Indikator berhasil dihapus.');
    }
}
