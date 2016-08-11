<?php

namespace Orchestra\Foundation\Notifications;

use Orchestra\Foundation\Auth\User;
use Illuminate\Notifications\Notification;
use Orchestra\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
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
        $this->token    = $token;
        $this->provider = $provider;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the notification message for mail.
     *
     * @param  mixed  $notifiable
     *
     * @return \Orchestra\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email   = urlencode($notifiable->getEmailForPasswordReset());
        $expired = config("auth.passwords.{$this->provider}.expire", 60);
        $title   = trans('orchestra/foundation::email.forgot.title');

        $message = new MailMessage();

        $message->title($title)
                    ->level('warning')
                    ->line(trans('orchestra/foundation::email.forgot.message.intro'))
                    ->action($title, handles("orchestra::forgot/reset/{$this->token}?email={$email}"))
                    ->line(trans('orchestra/foundation::email.forgot.message.expired_in', compact('expired')))
                    ->line(trans('orchestra/foundation::email.forgot.message.outro'));

        if (! is_null($view = config("auth.passwords.{$this->provider}.email"))) {
            $message->view($view);
        }

        return $message;
    }
}
