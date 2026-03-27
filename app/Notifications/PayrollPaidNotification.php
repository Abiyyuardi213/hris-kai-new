<?php

namespace App\Notifications;

use App\Models\Payroll;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class PayrollPaidNotification extends Notification implements ShouldQueue
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
        $monthName = Carbon::create()->month($this->payroll->month)->translatedFormat('F');
        $year = $this->payroll->year;

        return (new MailMessage)
            ->subject('[KAI-HRIS] Gaji Anda Telah Disetujui - Periode ' . $monthName . ' ' . $year)
            ->greeting('Halo, ' . $notifiable->nama_lengkap)
            ->line('Kabar baik! Payroll Anda untuk periode ' . $monthName . ' ' . $year . ' telah disetujui dan berstatus PAID (Dibayar).')
            ->line('Silakan periksa detailnya melalui tautan di bawah ini.')
            ->action('Lihat Detail Slip Gaji', route('employee.payroll.index'))
            ->line('Terima kasih atas dedikasi Anda.')
            ->line('Sistem HRIS PT Kereta Api Indonesia (Persero)');
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
            'title' => 'Payroll Disetujui',
            'message' => 'Gaji Anda untuk periode ' . $monthName . ' ' . $this->payroll->year . ' telah disetujui dan berstatus PAID.',
            'url' => route('employee.payroll.index'),
            'type' => 'success',
            'icon' => 'check-circle',
        ];
    }
}
