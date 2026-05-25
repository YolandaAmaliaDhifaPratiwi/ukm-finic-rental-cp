<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke member ketika admin menyetujui peminjaman.
 */
class BorrowingApprovedNotification extends Notification
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
        return [
            'title'   => 'Peminjaman Disetujui',
            'message' => $this->borrowing
                ? "Peminjaman alat \"{$this->borrowing->equipment->name}\" telah disetujui admin."
                : __('member.notif_approved'),
            'type'    => 'borrow_approved',
        ];
    }
}
