<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    public string $message, $time, $totalUnread;

    public function __construct(string $message, string $time, int $totalUnread)
    {
        $this->message = $message;
        $this->time = $time;
        $this->totalUnread = $totalUnread;
    }

    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

   public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => "$this->message",
            'time' => "$this->time",
            'totalUnread' => "$this->totalUnread"
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
