<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kriteria; // Asumsi nama model adalah Kriteria

use App\Models\Subkriteria; // Menggunakan nama model yang benar

class KriteriaController extends Controller
{
    /**
     * Menampilkan daftar semua kriteria beserta sub-kriterianya.
     */
    public function index()
    {
        $kriterias = Kriteria::with('subkriterias')->latest()->get();
        return view('pages.daftar_kriteria_auditor', compact('kriterias'));
    }

    /**
     * Menampilkan form untuk membuat kriteria baru.
     */
    public function create()
    {
        return view('pages.insert_kriteria_auditor');
    }

    /**
     * Menyimpan kriteria baru ke database.
     */
    /**
     * Menampilkan form untuk mengedit kriteria.
     */
    public function edit(Kriteria $kriteria)
    {
        return view('pages.edit_kriteria_auditor', compact('kriteria'));
    }

    /**
     * Memperbarui kriteria di database.
     */
    public function update(Request $request, Kriteria $kriteria)
    {
        $validatedData = $request->validate([
            'nama_kriteria' => 'required|string|max:255|unique:kriterias,nama_kriteria,' . $kriteria->id,
            'deskripsi_kriteria' => 'required|string',
        ]);

        $kriteria->update($validatedData);

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil diperbarui!');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_kriteria' => 'required|string|max:255|unique:kriterias',
            'deskripsi_kriteria' => 'required|string',
        ]);

        // Simpan ke database
        Kriteria::create($validatedData);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('kriteria.create')->with('success', 'Kriteria berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk membuat sub-kriteria baru.
     */
    public function createSubKriteria()
    {
        // Mengambil semua kriteria untuk ditampilkan di dropdown
        $kriterias = Kriteria::all();
        return view('pages.insert_sub_kriteria_auditor', compact('kriterias'));
    }

    /**
     * Menyimpan sub-kriteria baru ke database.
     */
    /**
     * Menghapus kriteria dari database.
     */
    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil dihapus!');
    }

    public function storeSubKriteria(Request $request)
    {
        // Validasi input
        $request->validate([
            'kriteria_id' => 'required|exists:kriterias,id',
            'nama_sub_kriteria' => 'required|string|max:255',
            'deskripsi_sub_kriteria' => 'required|string',
        ]);

        // Simpan ke database menggunakan model Subkriteria
        Subkriteria::create([
            'kriteria_id' => $request->kriteria_id,
            'nama_subkriteria' => $request->nama_sub_kriteria,
            'deskripsi_subkriteria' => $request->deskripsi_sub_kriteria,
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('insert.sub.kriteria.auditor')->with('success', 'Sub-Kriteria berhasil ditambahkan!');
    }

    /**
     * Show the form to edit an existing sub-kriteria.
     */
    public function editSubKriteria(Subkriteria $subkriteria)
    {
        $kriterias = Kriteria::all();
        return view('pages.edit_sub_kriteria_auditor', compact('subkriteria', 'kriterias'));
    }

    /**
     * Update the specified sub-kriteria in storage.
     */
    public function updateSubKriteria(Request $request, Subkriteria $subkriteria)
    {
        $validated = $request->validate([
            'kriteria_id' => 'required|exists:kriterias,id',
            'nama_sub_kriteria' => 'required|string|max:255',
            'deskripsi_sub_kriteria' => 'required|string',
        ]);
        $subkriteria->update([
            'kriteria_id' => $validated['kriteria_id'],
            'nama_subkriteria' => $validated['nama_sub_kriteria'],
            'deskripsi_subkriteria' => $validated['deskripsi_sub_kriteria'],
        ]);
        return redirect()->route('kriteria.index')->with('success', 'Sub-Kriteria berhasil diperbarui!');
    }

    /**
     * Remove the specified sub-kriteria from storage.
     */
    public function destroySubKriteria(Subkriteria $subkriteria)
    {
        $subkriteria->delete();
        return redirect()->route('kriteria.index')->with('success', 'Sub-Kriteria berhasil dihapus!');
    }
}
