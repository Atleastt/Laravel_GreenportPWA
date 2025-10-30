<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\DashboardAuditorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndikatorController;
use App\Http\Controllers\BuktiPendukungController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\VisitasiLapanganController;
use App\Http\Controllers\IndikatorDokumenController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DocumentUploadTestController;
use App\Http\Controllers\Auditee\DashboardController as AuditeeDashboardController;
use App\Http\Controllers\NotificationController;

// Redirect root ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

/*
|-----------------------------------
| Rute yang Memerlukan Autentikasi
|-----------------------------------
*/
Route::middleware([
    'auth',
    'verified',
])->group(function () {

    // Dashboard default Jetstream
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard Auditor - route alternatif jika diperlukan
    Route::get('/dashboard-auditor', [DashboardController::class, 'index'])->middleware('role:Auditor')->name('dashboard.auditor');

    // Manajemen Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin: Manajemen Kriteria
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('kriteria', KriteriaController::class);
    });

    // ---------- Halaman Statis/Mockup ----------
    Route::get('dashboard-auditee', [AuditeeDashboardController::class, 'index'])->middleware('role:Auditee')->name('dashboard.auditee');
    Route::middleware('role:Auditor')->get('daftar-audit-auditor', [AuditController::class, 'index'])->name('daftar.audit.auditor');
    Route::view('detail-audit-auditee', 'pages.detail_audit_auditee', [
        'audit' => null,
        'laporan' => null,
    ])->middleware('role:Auditee')->name('detail.audit.auditee');
    Route::middleware('role:Auditor')->get('form-buat-audit-auditor', [AuditController::class, 'create'])->name('form.buat.audit.auditor');
    Route::middleware('role:Auditor')->post('audits', [AuditController::class, 'store'])->name('audits.store');
    Route::middleware('role:Auditor')->get('audits/{audit}', [AuditController::class, 'show'])->name('audits.show');
    Route::middleware('role:Auditor')->get('audits/{audit}/edit', [AuditController::class, 'edit'])->name('audits.edit');
    Route::middleware('role:Auditor')->patch('audits/{audit}', [AuditController::class, 'update'])->name('audits.update');
    Route::middleware('role:Auditor')->delete('audits/{audit}', [AuditController::class, 'destroy'])->name('audits.destroy');

    // CRUD Indikator
    Route::resource('indikator', IndikatorController::class)->middleware('role:Auditor');

    // Rute untuk data dashboard auditor
    Route::get('/dashboard/auditor/stats', [DashboardAuditorController::class, 'getStats'])->name('dashboard.auditor.stats');

    // Rute untuk pelaporan
    Route::get('/pelaporan', [LaporanController::class, 'index'])->name('pelaporan.index');
    Route::get('/laporan/create/{audit}', [LaporanController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');

    // Rute untuk riwayat audit
    Route::get('/history', [AuditController::class, 'history'])->name('history.index');
    Route::get('/history/{audit}/report', [AuditController::class, 'showReport'])->name('history.report');

    // Rute untuk indikator dokumen
    Route::resource('indikator-dokumen', IndikatorDokumenController::class);

    // Rute untuk visitasi lapangan
    Route::resource('visitasi-lapangan', VisitasiLapanganController::class);

    // Rute untuk bukti pendukung
    Route::resource('bukti-pendukung', BuktiPendukungController::class);

    // Rute untuk notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Rute untuk test upload dokumen
    Route::get('/test-upload', [DocumentUploadTestController::class, 'index'])->name('test.upload');
    Route::post('/test-upload', [DocumentUploadTestController::class, 'store'])->name('test.upload.store');

    // API route for network testing
    Route::post('/api/test-upload', [DocumentUploadTestController::class, 'apiUpload'])->name('api.test.upload');

    Route::view('laporan-audit-contoh', 'pages.laporan_audit_contoh')->name('laporan.audit.contoh');
    Route::view('forget-password-page', 'pages.forget_password')->name('forget.password.page');
    Route::view('tambah-dokumen', 'pages.tambah_dokumen')->name('tambah.dokumen');
    Route::view('form-upload-dokumen', 'pages.form_upload_dokumen')->name('form.upload.dokumen');
    Route::view('upload-sertifikat', 'pages.upload_sertifikat')->name('upload.sertifikat');
    Route::view('indikator-dokumen', 'pages.indikator_dokumen')->name('indikator.dokumen');
    Route::view('detail-audit-auditor', 'pages.detail_audit_auditor')->name('detail.audit.auditor');
    Route::view('form-edit-audit-auditor', 'pages.form_edit_audit_auditor')->name('form.edit.audit.auditor');
    Route::view('rekomendasi-auditee', 'pages.rekomendasi_auditee')->name('rekomendasi.auditee');
    Route::view('rekomendasi-auditor', 'pages.rekomendasi_auditor')->name('rekomendasi.auditor');
    Route::view('kelola-checklist', 'pages.kelola_checklist')->name('kelola.checklist');
    Route::view('history', 'pages.history')->name('history');
    Route::view('pelaporan', 'pages.pelaporan')->name('pelaporan');
    Route::view('hasil-penilaian', 'pages.hasil_penilaian')->name('hasil.penilaian');
    Route::view('visitasi-lapangan', 'pages.visitasi_lapangan')->name('visitasi.lapangan');
    Route::view('bukti-pendukung', 'pages.bukti_pendukung')->name('bukti.pendukung');

    // Notification Routes
    Route::prefix('notifikasi')->name('notifikasi.')->middleware('role:Auditor|Auditee')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::patch('/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::patch('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
    });

    Route::view('regulasi', 'pages.regulasi')->middleware('role:Auditor|Auditee')->name('regulasi');
    Route::view('forum', 'pages.forum')->middleware('role:Auditor|Auditee')->name('forum');
    Route::view('sertifikasi', 'pages.sertifikasi')->middleware('role:Auditor|Auditee')->name('sertifikasi');

    Route::get('daftar-kriteria-auditor', [App\Http\Controllers\KriteriaController::class, 'index'])->middleware('role:Auditor')->name('kriteria.index');
    Route::get('insert-kriteria-auditor', [App\Http\Controllers\KriteriaController::class, 'create'])->middleware('role:Auditor')->name('kriteria.create');
    Route::post('insert-kriteria-auditor', [App\Http\Controllers\KriteriaController::class, 'store'])->middleware('role:Auditor')->name('kriteria.store');
    Route::get('kriteria/{kriteria}/edit', [App\Http\Controllers\KriteriaController::class, 'edit'])->middleware('role:Auditor')->name('kriteria.edit');
    Route::put('kriteria/{kriteria}', [App\Http\Controllers\KriteriaController::class, 'update'])->middleware('role:Auditor')->name('kriteria.update');
    Route::delete('kriteria/{kriteria}', [App\Http\Controllers\KriteriaController::class, 'destroy'])->middleware('role:Auditor')->name('kriteria.destroy');
    Route::get('insert-sub-kriteria-auditor', [App\Http\Controllers\KriteriaController::class, 'createSubKriteria'])->middleware('role:Auditor')->name('insert.sub.kriteria.auditor');
    Route::post('insert-sub-kriteria-auditor', [App\Http\Controllers\KriteriaController::class, 'storeSubKriteria'])->middleware('role:Auditor')->name('subkriteria.store');
    Route::get('subkriteria/{subkriteria}/edit', [App\Http\Controllers\KriteriaController::class, 'editSubKriteria'])->middleware('role:Auditor')->name('subkriteria.edit');
    Route::put('subkriteria/{subkriteria}', [App\Http\Controllers\KriteriaController::class, 'updateSubKriteria'])->middleware('role:Auditor')->name('subkriteria.update');
    Route::delete('subkriteria/{subkriteria}', [App\Http\Controllers\KriteriaController::class, 'destroySubKriteria'])->middleware('role:Auditor')->name('subkriteria.destroy');
    Route::resource('bukti-pendukung', BuktiPendukungController::class)->middleware('role:Auditor|Auditee');
    // Tambah approve/reject bukti pendukung untuk Auditor
    Route::patch('bukti-pendukung/{bukti}/approve', [BuktiPendukungController::class, 'approve'])
        ->name('bukti-pendukung.approve')->middleware('role:Auditor');
    Route::patch('bukti-pendukung/{bukti}/reject', [BuktiPendukungController::class, 'reject'])
        ->name('bukti-pendukung.reject')->middleware('role:Auditor');
    // Route::view('profile-page', 'pages.profile')->name('profile.page'); // Route ini menyebabkan error karena tidak mengirimkan data user
    Route::get('history', [AuditController::class, 'history'])->name('history');
    Route::get('history/{audit}/report', [AuditController::class, 'showReport'])->name('history.report.audit');
    Route::get('hasil-penilaian', [AuditController::class, 'hasilPenilaian'])->middleware('role:Auditee')->name('hasil.penilaian');
    Route::get('pelaporan', [LaporanController::class, 'index'])->middleware('role:Auditor')->name('pelaporan');
    Route::post('pelaporan', [LaporanController::class, 'store'])->middleware('role:Auditor')->name('pelaporan.store');
    Route::get('laporan/{audit}/create', [LaporanController::class, 'create'])->middleware('role:Auditor')->name('laporan.create.audit');
    Route::view('tambah-pelaporan', 'pages.tambah_pelaporan')->middleware('role:Auditor')->name('tambah.pelaporan');
    Route::get('visitasi-lapangan', [VisitasiLapanganController::class, 'index'])->middleware('role:Auditor')->name('visitasi.lapangan');
    Route::post('visitasi-lapangan', [VisitasiLapanganController::class, 'store'])->middleware('role:Auditor')->name('visitasi.lapangan.store');
    Route::get('visitasi-lapangan/{visitasi}', [VisitasiLapanganController::class, 'show'])->middleware('role:Auditor')->name('visitasi.lapangan.show');
    Route::patch('visitasi-lapangan/{visitasi}/cancel', [VisitasiLapanganController::class, 'cancel'])->middleware('role:Auditor')->name('visitasi.lapangan.cancel');
    Route::view('tambah-history', 'pages.tambah_history')->name('tambah.history');

    // CRUD Indikator

    Route::resource('indikator-dokumen', IndikatorDokumenController::class)->middleware('role:Auditor');

    // Testing routes for document upload
    Route::prefix('test-upload')->name('test.upload.')->group(function () {
        Route::get('/', [\App\Http\Controllers\DocumentUploadTestController::class, 'index'])->name('index');
        Route::post('/2mb', [\App\Http\Controllers\DocumentUploadTestController::class, 'upload2MB'])->name('2mb');
        Route::post('/5mb', [\App\Http\Controllers\DocumentUploadTestController::class, 'upload5MB'])->name('5mb');
        Route::post('/10mb', [\App\Http\Controllers\DocumentUploadTestController::class, 'upload10MB'])->name('10mb');
        Route::post('/50mb', [\App\Http\Controllers\DocumentUploadTestController::class, 'upload50MB'])->name('50mb');
        Route::get('/results', [\App\Http\Controllers\DocumentUploadTestController::class, 'getTestResults'])->name('results');
        Route::delete('/clear', [\App\Http\Controllers\DocumentUploadTestController::class, 'clearTestData'])->name('clear');
        Route::post('/auto-test', [\App\Http\Controllers\DocumentUploadTestController::class, 'runAutomatedTests'])->name('auto');
        Route::post('/concurrent-test', [\App\Http\Controllers\DocumentUploadTestController::class, 'testConcurrentUploads'])->name('concurrent');
        Route::get('/system-info', [\App\Http\Controllers\DocumentUploadTestController::class, 'getSystemInfo'])->name('system');
    });

    // Network condition testing route
    Route::get('/network-test', function () {
        return view('pages.network-test');
    })->name('network.test');

    // PWA Offline Testing Route
    Route::get('/pwa-test-runner', function () {
        return view('pages.pwa-test-runner');
    })->name('pwa.test.runner');

    // PWA Offline Testing Routes
    Route::prefix('pwa-test')->name('pwa.test.')->group(function () {
        Route::post('/submit-evidence', [\App\Http\Controllers\PWATestController::class, 'submitEvidence'])->name('submit-evidence');
        Route::get('/results', [\App\Http\Controllers\PWATestController::class, 'getTestResults'])->name('results');
        Route::get('/report', [\App\Http\Controllers\PWATestController::class, 'generateTestReport'])->name('report');
        Route::get('/export', [\App\Http\Controllers\PWATestController::class, 'exportTestResults'])->name('export');
        Route::get('/validate-integrity', [\App\Http\Controllers\PWATestController::class, 'validateDataIntegrity'])->name('validate-integrity');
        Route::delete('/cleanup', [\App\Http\Controllers\PWATestController::class, 'cleanupTestData'])->name('cleanup');
    });

    // API Routes for PWA testing
    Route::prefix('api')->name('api.')->group(function () {
        Route::post('/auditee/submit-evidence', [\App\Http\Controllers\PWATestController::class, 'submitEvidence'])->name('auditee.submit-evidence');
    });

    /*
    |--------------------------------------------------------------------------
    | Rute Khusus Auditor (role:Auditor)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'verified', 'role:Auditor'])->prefix('auditor')->name('auditor.')->group(function () {
        Route::resource('kriteria', \App\Http\Controllers\Auditor\KriteriaController::class);
        // CRUD Checklist & Kepatuhan
        Route::resource('checklist-templates', \App\Http\Controllers\Auditor\ChecklistTemplateController::class);
        Route::resource('subkriteria', \App\Http\Controllers\Auditor\SubkriteriaController::class);
        Route::resource('indikator', \App\Http\Controllers\Auditor\IndikatorController::class);
        Route::resource('audits', \App\Http\Controllers\Auditor\AuditController::class);

        // Auditor Review
        Route::post('reviews/{audit}/criterion/{criterion}', [\App\Http\Controllers\Auditor\ReviewController::class, 'store'])->name('reviews.store');

        Route::get('reviews/{audit}', [\App\Http\Controllers\Auditor\ReviewController::class, 'show'])->name('reviews.show');
        Route::post('reviews/{audit}/items/{item}/score', [\App\Http\Controllers\Auditor\ReviewController::class, 'storeScore'])->name('reviews.storeScore');
        Route::post('reviews/{audit}/finalize', [\App\Http\Controllers\Auditor\ReviewController::class, 'finalize'])->name('reviews.finalize');
    });

    /*
    |--------------------------------------------------------------------------
    | Rute Khusus Auditee (role:Auditee)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'verified', 'role:Auditee'])->prefix('auditee')->name('auditee.')->group(function () {
        Route::get('tugas', [\App\Http\Controllers\Auditee\TugasController::class, 'index'])->name('tugas.index');
        Route::get('tugas/{audit}', [\App\Http\Controllers\Auditee\TugasController::class, 'show'])->name('tugas.show');
        Route::get('tugas/{audit}/checklist', [\App\Http\Controllers\Auditee\ChecklistController::class, 'show'])->name('tugas.checklist');
        Route::post('tugas/{audit}/checklist', [\App\Http\Controllers\Auditee\ChecklistController::class, 'store'])->name('tugas.checklist.store');
        Route::get('tugas/{audit}/criterion/{criterion}/edit', [\App\Http\Controllers\Auditee\TugasController::class, 'editTindakLanjut'])->name('tindak_lanjut.edit');
        Route::post('tugas/{audit}/criterion/{criterion}', [\App\Http\Controllers\Auditee\TugasController::class, 'updateTindakLanjut'])->name('tindak_lanjut.update');
        Route::patch('tugas/{audit}/items/{item}', [\App\Http\Controllers\Auditee\ChecklistController::class, 'updateItem'])->name('tugas.items.update');
        Route::post('uploads', [\App\Http\Controllers\Auditee\UploadController::class, 'store'])->name('uploads.store');
    });

    // Notification Routes
    Route::middleware('role:Auditor|Auditee')->group(function () {
        Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::post('notifications/send-reminder', [App\Http\Controllers\NotificationController::class, 'sendAuditReminder'])->name('notifications.send-reminder');
    });

    // Rekomendasi Routes
    Route::middleware('role:Auditor|Auditee')->group(function () {
        Route::get('rekomendasi', [App\Http\Controllers\RekomendasiController::class, 'index'])->name('rekomendasi.index');
        Route::get('rekomendasi/{audit}/create', [App\Http\Controllers\RekomendasiController::class, 'create'])->name('rekomendasi.create');
        Route::post('rekomendasi', [App\Http\Controllers\RekomendasiController::class, 'store'])->name('rekomendasi.store');
        Route::get('rekomendasi/{rekomendasi}', [App\Http\Controllers\RekomendasiController::class, 'show'])->name('rekomendasi.show');
        Route::get('rekomendasi/{rekomendasi}/edit', [App\Http\Controllers\RekomendasiController::class, 'edit'])->name('rekomendasi.edit');
        Route::patch('rekomendasi/{rekomendasi}', [App\Http\Controllers\RekomendasiController::class, 'update'])->name('rekomendasi.update');
        Route::delete('rekomendasi/{rekomendasi}', [App\Http\Controllers\RekomendasiController::class, 'destroy'])->name('rekomendasi.destroy');
        Route::patch('rekomendasi/{rekomendasi}/status', [App\Http\Controllers\RekomendasiController::class, 'updateStatus'])->name('rekomendasi.update-status');

        // Pages specific route for auditor view
        Route::get('pages/rekomendasi-auditor', [App\Http\Controllers\RekomendasiController::class, 'auditorView'])->name('pages.rekomendasi_auditor');
    });

    // Tindak Lanjut Routes
    Route::middleware('role:Auditor|Auditee')->group(function () {
        Route::get('tindak-lanjut', [App\Http\Controllers\TindakLanjutController::class, 'index'])->name('tindak-lanjut.index');
        Route::get('tindak-lanjut/create', [App\Http\Controllers\TindakLanjutController::class, 'create'])->name('tindak-lanjut.create');
        Route::post('tindak-lanjut', [App\Http\Controllers\TindakLanjutController::class, 'store'])->name('tindak-lanjut.store');
        Route::get('tindak-lanjut/{tindakLanjut}', [App\Http\Controllers\TindakLanjutController::class, 'show'])->name('tindak-lanjut.show');
        Route::get('tindak-lanjut/{tindakLanjut}/edit', [App\Http\Controllers\TindakLanjutController::class, 'edit'])->name('tindak-lanjut.edit');
        Route::patch('tindak-lanjut/{tindakLanjut}', [App\Http\Controllers\TindakLanjutController::class, 'update'])->name('tindak-lanjut.update');
        Route::delete('tindak-lanjut/{tindakLanjut}', [App\Http\Controllers\TindakLanjutController::class, 'destroy'])->name('tindak-lanjut.destroy');
        Route::patch('tindak-lanjut/{tindakLanjut}/progress', [App\Http\Controllers\TindakLanjutController::class, 'updateProgress'])->name('tindak-lanjut.update-progress');
        Route::patch('tindak-lanjut/{tindakLanjut}/milestone/{milestoneIndex}', [App\Http\Controllers\TindakLanjutController::class, 'updateMilestone'])->name('tindak-lanjut.update-milestone');
    });

    // Enhanced Riwayat Audit with Comparison
    Route::middleware('role:Auditor|Auditee')->group(function () {
        Route::get('riwayat-audit', [App\Http\Controllers\AuditController::class, 'history'])->name('audit.history');
        Route::get('riwayat-audit/{audit1}/compare/{audit2}', [App\Http\Controllers\AuditController::class, 'compare'])->name('audit.compare');
        Route::get('riwayat-audit/{audit}/report', [App\Http\Controllers\AuditController::class, 'showReport'])->name('history.report');
    });

});

require __DIR__.'/auth.php';
