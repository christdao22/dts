<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SeparationOfServiceNotif extends Notification
{
    use Queueable;

    public string $message, $time, $totalUnread;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $message, string $time, int $totalUnread)
    {
        $this->message = $message;
        $this->time = $time;
        $this->totalUnread = $totalUnread;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => "$this->message",
            'time' => "$this->time",
            'totalUnread' => "$this->totalUnread"
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
