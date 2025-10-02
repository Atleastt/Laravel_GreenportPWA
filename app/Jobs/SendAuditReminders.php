<?php

namespace App\Jobs;

use App\Models\Audit;
use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use App\Notifications\AuditNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SendAuditReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow();
        $in3Days = Carbon::now()->addDays(3);
        $in7Days = Carbon::now()->addDays(7);

        // Send audit deadline reminders
        $this->sendAuditDeadlineReminders($tomorrow, $in3Days, $in7Days);
        
        // Send recommendation deadline reminders
        $this->sendRecommendationDeadlineReminders($tomorrow, $in3Days, $in7Days);
        
        // Send follow-up deadline reminders
        $this->sendFollowUpDeadlineReminders($tomorrow, $in3Days, $in7Days);
        
        // Send overdue notifications
        $this->sendOverdueNotifications();
    }

    private function sendAuditDeadlineReminders($tomorrow, $in3Days, $in7Days)
    {
        // Audits ending tomorrow
        Audit::where('scheduled_end_date', $tomorrow->toDateString())
             ->where('status', '!=', 'Completed')
             ->with(['auditor', 'auditee'])
             ->get()
             ->each(function ($audit) {
                 $audit->auditor->notify(new AuditNotification(
                     "Audit '{$audit->title}' akan berakhir besok!",
                     $audit->id,
                     'deadline'
                 ));
                 
                 $audit->auditee->notify(new AuditNotification(
                     "Audit '{$audit->title}' akan berakhir besok! Pastikan semua dokumen telah diunggah.",
                     $audit->id,
                     'deadline'
                 ));
             });

        // Audits ending in 3 days
        Audit::where('scheduled_end_date', $in3Days->toDateString())
             ->where('status', '!=', 'Completed')
             ->with(['auditor', 'auditee'])
             ->get()
             ->each(function ($audit) {
                 $audit->auditor->notify(new AuditNotification(
                     "Audit '{$audit->title}' akan berakhir dalam 3 hari.",
                     $audit->id,
                     'reminder'
                 ));
                 
                 $audit->auditee->notify(new AuditNotification(
                     "Audit '{$audit->title}' akan berakhir dalam 3 hari. Persiapkan dokumen yang diperlukan.",
                     $audit->id,
                     'reminder'
                 ));
             });
    }

    private function sendRecommendationDeadlineReminders($tomorrow, $in3Days, $in7Days)
    {
        // Recommendations due tomorrow
        Rekomendasi::where('batas_waktu', $tomorrow->toDateString())
                   ->where('status', '!=', 'Selesai')
                   ->with(['auditor', 'audit.auditee'])
                   ->get()
                   ->each(function ($rekomendasi) {
                       $rekomendasi->audit->auditee->notify(new AuditNotification(
                           "Rekomendasi '{$rekomendasi->deskripsi_temuan}' harus diselesaikan besok!",
                           $rekomendasi->audit_id,
                           'deadline'
                       ));
                       
                       $rekomendasi->auditor->notify(new AuditNotification(
                           "Rekomendasi '{$rekomendasi->deskripsi_temuan}' akan jatuh tempo besok.",
                           $rekomendasi->audit_id,
                           'deadline'
                       ));
                   });

        // Recommendations due in 3 days
        Rekomendasi::where('batas_waktu', $in3Days->toDateString())
                   ->where('status', '!=', 'Selesai')
                   ->with(['auditor', 'audit.auditee'])
                   ->get()
                   ->each(function ($rekomendasi) {
                       $rekomendasi->audit->auditee->notify(new AuditNotification(
                           "Pengingat: Rekomendasi '{$rekomendasi->deskripsi_temuan}' akan jatuh tempo dalam 3 hari.",
                           $rekomendasi->audit_id,
                           'reminder'
                       ));
                   });
    }

    private function sendFollowUpDeadlineReminders($tomorrow, $in3Days, $in7Days)
    {
        // Follow-ups due tomorrow
        TindakLanjut::where('target_penyelesaian', $tomorrow->toDateString())
                    ->where('status_progres', '!=', 'Selesai')
                    ->with(['auditee', 'rekomendasi.auditor'])
                    ->get()
                    ->each(function ($tindakLanjut) {
                        $tindakLanjut->auditee->notify(new AuditNotification(
                            "Tindak lanjut '{$tindakLanjut->rencana_tindakan}' harus diselesaikan besok!",
                            $tindakLanjut->rekomendasi->audit_id,
                            'deadline'
                        ));
                        
                        $tindakLanjut->rekomendasi->auditor->notify(new AuditNotification(
                            "Tindak lanjut akan jatuh tempo besok: '{$tindakLanjut->rencana_tindakan}'",
                            $tindakLanjut->rekomendasi->audit_id,
                            'deadline'
                        ));
                    });
    }

    private function sendOverdueNotifications()
    {
        $today = Carbon::today();

        // Overdue audits
        Audit::where('scheduled_end_date', '<', $today->toDateString())
             ->where('status', '!=', 'Completed')
             ->with(['auditor', 'auditee'])
             ->get()
             ->each(function ($audit) {
                 $daysOverdue = Carbon::parse($audit->scheduled_end_date)->diffInDays(now(), false);
                 
                 $audit->auditor->notify(new AuditNotification(
                     "Audit '{$audit->title}' telah melewati batas waktu {$daysOverdue} hari!",
                     $audit->id,
                     'overdue'
                 ));
             });

        // Overdue recommendations
        Rekomendasi::where('batas_waktu', '<', $today->toDateString())
                   ->where('status', '!=', 'Selesai')
                   ->with(['auditor', 'audit.auditee'])
                   ->get()
                   ->each(function ($rekomendasi) {
                       $daysOverdue = Carbon::parse($rekomendasi->batas_waktu)->diffInDays(now(), false);
                       
                       $rekomendasi->audit->auditee->notify(new AuditNotification(
                           "Rekomendasi telah melewati batas waktu {$daysOverdue} hari: '{$rekomendasi->deskripsi_temuan}'",
                           $rekomendasi->audit_id,
                           'overdue'
                       ));
                       
                       $rekomendasi->auditor->notify(new AuditNotification(
                           "Rekomendasi terlambat {$daysOverdue} hari: '{$rekomendasi->deskripsi_temuan}'",
                           $rekomendasi->audit_id,
                           'overdue'
                       ));
                   });

        // Overdue follow-ups
        TindakLanjut::where('target_penyelesaian', '<', $today->toDateString())
                    ->where('status_progres', '!=', 'Selesai')
                    ->with(['auditee', 'rekomendasi.auditor'])
                    ->get()
                    ->each(function ($tindakLanjut) {
                        $daysOverdue = Carbon::parse($tindakLanjut->target_penyelesaian)->diffInDays(now(), false);
                        
                        $tindakLanjut->auditee->notify(new AuditNotification(
                            "Tindak lanjut terlambat {$daysOverdue} hari: '{$tindakLanjut->rencana_tindakan}'",
                            $tindakLanjut->rekomendasi->audit_id,
                            'overdue'
                        ));
                        
                        $tindakLanjut->rekomendasi->auditor->notify(new AuditNotification(
                            "Tindak lanjut terlambat {$daysOverdue} hari: '{$tindakLanjut->rencana_tindakan}'",
                            $tindakLanjut->rekomendasi->audit_id,
                            'overdue'
                        ));
                    });
    }
}
