<?php

namespace Orchestra\Foundation\Notifications;

use Orchestra\Foundation\Auth\User;
use Orchestra\Notifications\Notification;

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
     * Get the notification's options.
     *
     * @return array
     */
    public function options()
    {
        return [
            'view' => config("auth.passwords.{$this->provider}.email"),
        ];
    }

    /**
     * Get the title of the notification.
     *
     * @return string
     */
    public function title()
    {
        return trans('orchestra/foundation::email.forgot.request');
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
                    ->action('Reset Password', handles("orchestra::forgot/reset/{$this->token}?email={$email}"))
                    ->line("This link will expire in {$expired} minutes.")
                    ->line('If you did not request a password reset, no further action is required.');
    }
}
