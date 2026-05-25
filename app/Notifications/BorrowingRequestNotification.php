<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke ADMIN ketika member mengajukan permintaan peminjaman baru.
 */
class BorrowingRequestNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $borrowing;

    public function __construct($user, $borrowing)
    {
        $this->user      = $user;
        $this->borrowing = $borrowing;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'        => 'Permintaan Peminjaman Baru',
            'message'      => "{$this->user->name} mengajukan peminjaman alat \"{$this->borrowing->equipment->name}\".",
            'type'         => 'borrow_request',
            'borrowing_id' => $this->borrowing->id,
        ];
    }
}
