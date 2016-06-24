<?php

namespace Orchestra\Foundation\Auth\Notifications;

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
     * @param  mixed  $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
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
        $site = memorize('site.name', 'Orchestra Platform');

        return trans('orchestra/foundation::email.forgot.request', compact('site'));
    }

    /**
     * Get the notification message.
     *
     * @param  mixed  $notifiable
     *
     * @return \Illuminate\Notifications\MessageBuilder
     */
    public function message($notifiable)
    {
        $email = urlencode($notifiable->email);

        return $this->line('You are receiving this email because we received a password reset request for your account. Click the button below to reset your password:')
                    ->action('Reset Password', handles("orchestra::forgot/reset{$this->token}?email={$email}"))
                    ->line('If you did not request a password reset, no further action is required.');
    }
}
