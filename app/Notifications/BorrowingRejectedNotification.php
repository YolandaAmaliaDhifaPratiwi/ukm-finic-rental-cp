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
        return [
            'title'   => 'Peminjaman Ditolak',
            'message' => $this->borrowing
                ? "Peminjaman alat \"{$this->borrowing->equipment->name}\" ditolak oleh admin."
                : 'Permintaan peminjaman kamu ditolak.',
            'type'    => 'borrow_rejected',
        ];
    }
}
