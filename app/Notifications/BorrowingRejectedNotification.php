<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke member ketika admin menolak peminjaman.
 */
class BorrowingRejectedNotification extends Notification
{
    use Queueable;

    protected $borrowing;

    public function __construct($borrowing = null)
    {
        $this->borrowing = $borrowing;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $equipmentName = $this->borrowing?->equipment?->name ?? 'Alat';
        $adminNotes    = $this->borrowing?->admin_notes ?? null;

        return [
            'title'       => 'Peminjaman Ditolak',
            'message'     => "Peminjaman alat \"{$equipmentName}\" ditolak oleh admin.",
            'type'        => 'borrow_rejected',
            // Alasan penolakan — ditampilkan di notifikasi member
            'reason'      => $adminNotes,
            'borrowing_id'=> $this->borrowing?->id,
        ];
    }
}