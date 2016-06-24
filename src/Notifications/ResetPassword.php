<?php

namespace Orchestra\Foundation\Notifications;

use Orchestra\Foundation\Auth\User;
use Illuminate\Notifications\Notification;

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
     * The "level" of the notification (info, success, error).
     *
     * @var string
     */
    public $level = 'warning';

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @param  string|null  $provider
     *
     * @return void
     */
    public function __construct($token, $provider = 'users')
    {
        $this->token    = $token;
        $this->provider = $provider;
    }

    /**
     * Get the notification's channels.
     *
     * @param  \Orchestra\Foundation\Auth\User  $notifiable
     *
     * @return array|string
     */
    public function via(User $notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the notification channel payload data.
     *
     * @return array
     */
    public function payload()
    {
        return ['view' => config("auth.passwords.{$this->provider}.email")];
    }

    /**
     * Get the subject of the notification.
     *
     * @return string
     */
    public function subject()
    {
        $application = memorize('site.name', 'Orchestra Platform');

        return trans('orchestra/foundation::email.forgot.request', compact('application'));
    }

    /**
     * Get the notification message.
     *
     * @param  \Orchestra\Foundation\Auth\User  $notifiable
     *
     * @return \Illuminate\Notifications\MessageBuilder
     */
    public function message(User $notifiable)
    {
        $email   = urlencode($notifiable->getEmailForPasswordReset());
        $expired = config("auth.passwords.{$this->provider}.expire", 60);

        return $this->line('You are receiving this email because we received a password reset request for your account. Click the button below to reset your password:')
                    ->action('Reset Password', handles("orchestra::forgot/reset{$this->token}?email={$email}"))
                    ->line("This link will expire in {$expired} minutes.")
                    ->line('If you did not request a password reset, no further action is required.');
    }
}
