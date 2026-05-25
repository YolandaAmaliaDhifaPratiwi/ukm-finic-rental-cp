<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReturnSubmittedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $borrowing;
    protected $itemReturn;

    public function __construct($user, $borrowing, $itemReturn)
    {
        $this->user       = $user;
        $this->borrowing  = $borrowing;
        $this->itemReturn = $itemReturn;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'     => 'Pengajuan Pengembalian Baru',
            'message'   => "{$this->user->name} mengajukan pengembalian alat \"{$this->borrowing->equipment->name}\".",
            'type'      => 'return_submitted',
            'return_id' => $this->itemReturn->id,
        ];
    }
}