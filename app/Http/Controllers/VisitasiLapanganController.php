<?php

namespace App\Http\Controllers;

use App\Models\VisitasiLapangan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class VisitasiLapanganController extends Controller
{
    public function index(): View
    {
        $jadwalVisitasi = VisitasiLapangan::orderBy('tanggal_visitasi', 'desc')->get();

        return view('pages.visitasi_lapangan', [
            'jadwalVisitasi' => $jadwalVisitasi
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'auditee_name' => 'required|string|max:255',
            'visit_date' => 'required|date',
            'visit_notes' => 'nullable|string',
        ]);

        VisitasiLapangan::create([
            'auditor_name' => Auth::user()->name,
            'auditee_name' => $request->auditee_name,
            'tanggal_visitasi' => $request->visit_date,
            'catatan' => $request->visit_notes,
        ]);

        return redirect()->route('visitasi.lapangan')->with('success', 'Jadwal visitasi berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail visitasi tertentu.
     */
    public function show(VisitasiLapangan $visitasi): View
    {
        return view('pages.detail_visitasi_lapangan', ['visitasi' => $visitasi]);
    }

    /**
     * Batalkan visitasi tertentu.
     */
    public function cancel(VisitasiLapangan $visitasi): RedirectResponse
    {
        $visitasi->status = 'Dibatalkan';
        $visitasi->save();

        return redirect()->route('visitasi.lapangan')->with('success', 'Visitasi berhasil dibatalkan.');
    }
}
