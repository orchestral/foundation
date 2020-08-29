<?php

namespace Orchestra\Foundation\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Orchestra\Notifications\Messages\MailMessage;

class Welcome extends Notification
{
    use Queueable;

    /**
     * The password.
     *
     * @var string|null
     */
    public $password;

    /**
     * Create a notification instance.
     *
     * @param  string|null  $password
     */
    public function __construct($password = null)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->markdown('orchestra/foundation::notifications.emails.welcome', [
                'email' => $notifiable->getRecipientEmail(),
                'fullname' => $notifiable->getRecipientName(),
                'password' => $this->password,
            ]);
    }
}
