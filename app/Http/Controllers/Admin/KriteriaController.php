<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kriterias = Kriteria::latest()->paginate(10);
        return view('admin.kriteria.index', compact('kriterias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kriteria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kriteria' => 'required|string|max:255|unique:kriterias',
            'deskripsi_kriteria' => 'required|string',
        ]);

        Kriteria::create($validatedData);

        return redirect()->route('admin.kriteria.index')
                         ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kriteria $kriterium)
    {
        return view('admin.kriteria.show', ['kriteria' => $kriterium]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kriteria $kriterium)
    {
        return view('admin.kriteria.edit', ['kriteria' => $kriterium]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kriteria $kriterium)
    {
        $request->validate([
            'nama_kriteria' => 'required|string|max:255|unique:kriterias,nama_kriteria,' . $kriterium->id,
            'deskripsi_kriteria' => 'required|string',
        ]);

        $kriterium->update($request->all());

        return redirect()->route('admin.kriteria.index')
                         ->with('success', 'Kriteria berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kriteria $kriterium)
    {
        $kriterium->delete();

        return redirect()->route('admin.kriteria.index')
                         ->with('success', 'Kriteria berhasil dihapus.');
    }
}

