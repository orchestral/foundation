<?php

namespace Orchestra\Foundation\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Orchestra\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The password reset user provider.
     *
     * @var string|null
     */
    public $provider;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @param  string|null  $provider
     */
    public function __construct($token, $provider = 'users')
    {
        $this->token = $token;
        $this->provider = $provider;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email = $notifiable->getEmailForPasswordReset();
        $url = \config('orchestra/foundation::routes.reset', 'orchestra::forgot/reset');

        return (new MailMessage())
            ->title(\trans('orchestra/foundation::email.forgot.title'))
            ->markdown('notifications.emails.reset-password', [
                'email' => $email,
                'fullname' => $notifiable->getRecipientName(),
                'url' => \handles("{$url}/{$this->token}?email=".\urlencode($email)),
                'expiredIn' => \config("auth.passwords.{$this->provider}.expire", 60);
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
