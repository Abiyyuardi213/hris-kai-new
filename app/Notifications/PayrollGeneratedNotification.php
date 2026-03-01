<?php

namespace App\Notifications;

use App\Models\Payroll;
use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class PayrollGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $payroll;

    public function __construct(Payroll $payroll)
    {
        $this->payroll = $payroll;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $payroll = $this->payroll->load(['pegawai.jabatan', 'pegawai.divisi']);
        $monthName = Carbon::create()->month($payroll->month)->translatedFormat('F');
        $year = $payroll->year;

        // Find Managing Director of Finance for electronic signature
        $mdFinance = Pegawai::whereHas('jabatan', function ($query) {
            $query->where('name', 'Managing Director of Finance');
        })->first();

        // If not found, use a default fallback (Indarto Pamoengkas / 654324)
        if (!$mdFinance) {
            $mdFinance = (object)[
                'nama_lengkap' => 'INDARTO PAMOENGKAS',
                'nip' => '654324'
            ];
        }

        // Generate PDF
        $pdf = Pdf::loadView('employee.payroll.pdf', compact('payroll', 'mdFinance'))->setPaper('a5');
        $pdfContent = $pdf->output();

        $fileName = 'Slip_Gaji_' . $notifiable->nip . '_' . $monthName . '_' . $year . '.pdf';

        return (new MailMessage)
            ->subject('[KAI-HRIS] Slip Gaji Periode ' . $monthName . ' ' . $year)
            ->greeting('Halo, ' . $notifiable->nama_lengkap)
            ->line('Slip gaji Anda untuk periode ' . $monthName . ' ' . $year . ' telah berhasil di-generate oleh sistem.')
            ->line('Terlampir adalah dokumen e-slip gaji resmi Anda dalam format PDF.')
            ->action('Lihat Detail di Dashboard', route('employee.payroll.show', $payroll->id))
            ->attachData($pdfContent, $fileName, [
                'mime' => 'application/pdf',
            ])
            ->line('Dokumen ini digenerate secara otomatis oleh sistem HRIS PT Kereta Api Indonesia (Persero) dan ditandatangani secara elektronik.')
            ->line('Terima kasih.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $monthName = Carbon::create()->month($this->payroll->month)->translatedFormat('F');
        return [
            'title' => 'Payroll Baru',
            'message' => 'Slip gaji Anda untuk periode ' . $monthName . ' ' . $this->payroll->year . ' telah tersedia.',
            'url' => route('employee.payroll.show', $this->payroll->id),
            'type' => 'success',
            'icon' => 'banknote',
        ];
    }
}
