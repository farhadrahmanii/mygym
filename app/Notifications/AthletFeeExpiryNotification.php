<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AthletFeeExpiryNotification extends Notification
{
    use Queueable;

    protected $athletes;

    public function __construct($athletes)
    {
        $this->athletes = $athletes;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->line('The fees for the following athletes are expiring tomorrow:');

        foreach ($this->athletes as $athlete) {
            $mailMessage->line('Name: ' . $athlete->name . ', Phone Number: ' . $athlete->phone_number);
        }

        $mailMessage->line('Please take the necessary actions.')
            ->action('View Athletes', url('/admin/athletes'))
            ->line('Thank you for using our application!');

        return $mailMessage;
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'The fees for some athletes are expiring tomorrow.',
            'athletes' => $this->athletes->pluck('id')->toArray(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'The fees for some athletes are expiring tomorrow.',
            'athletes' => $this->athletes->pluck('id')->toArray(),
        ]);
    }
}
