<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    private $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title'   => $this->details['title'],
            'message' => $this->details['message'],
            'link'    => $this->details['link'] ?? '#',
            'type'    => $this->details['type'] ?? 'info', // info, success, danger
        ];
    }
}