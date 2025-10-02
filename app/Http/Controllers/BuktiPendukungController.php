<?php

namespace App\Http\Controllers;

use App\Models\Bukti;
use App\Models\Temuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BuktiPendukungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil data bukti dengan relasi temuan, diurutkan dari yang terbaru
        $documents = Bukti::with('temuan')->latest()->get();
        // Ambil semua data temuan untuk dropdown di modal
        $temuans = Temuan::all();

        return view('pages.bukti_pendukung_auditee', compact('documents', 'temuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'temuan_id' => 'required|exists:temuans,id',
            'nama_dokumen' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:10240', // max 10MB for testing
        ]);

        $filePath = $request->file('file')->store('public/bukti_pendukung');

        Bukti::create([
            'temuan_id' => $request->temuan_id,
            'nama_dokumen' => $request->nama_dokumen,
            'file_path' => $filePath,
            'pengguna_unggah_id' => Auth::id(),
            'status' => 'menunggu verifikasi',
        ]);

        return redirect()->route('bukti-pendukung.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bukti = Bukti::findOrFail($id);

        // Pastikan file ada sebelum mencoba mengaksesnya
        if (!Storage::exists($bukti->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::response($bukti->file_path);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bukti = Bukti::findOrFail($id);

        // Hapus file dari storage
        if (Storage::exists($bukti->file_path)) {
            Storage::delete($bukti->file_path);
        }

        // Hapus record dari database
        $bukti->delete();

        return redirect()->route('bukti-pendukung.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Approve a document by Auditor.
     */
    public function approve($id)
    {
        $bukti = Bukti::findOrFail($id);
        $bukti->status = 'terverifikasi';
        $bukti->verified_by_user_id = Auth::id();
        $bukti->save();
        return redirect()->route('bukti-pendukung.index')->with('success', 'Dokumen berhasil diverifikasi.');
    }

    /**
     * Reject a document and request revision.
     */
    public function reject($id)
    {
        $bukti = Bukti::findOrFail($id);
        $bukti->status = 'revisi';
        $bukti->verified_by_user_id = Auth::id();
        $bukti->save();
        return redirect()->route('bukti-pendukung.index')->with('success', 'Dokumen dikembalikan untuk revisi.');
    }

    // Metode create, edit, dan update tidak kita gunakan untuk saat ini
    // karena form ada di dalam modal di halaman index.
    public function create() { return abort(404); }
    public function edit(string $id) { return abort(404); }
    public function update(Request $request, string $id) { return abort(404); }
}

