<?php

namespace Orchestra\Foundation\Notifications;

use Orchestra\Foundation\Auth\User;
use Orchestra\Notifications\Notification;

class Welcome extends Notification
{
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
     * Get the title of the notification.
     *
     * @return string
     */
    public function title()
    {
        return trans('orchestra/foundation::email.credential.register');
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
        $message = $this->line('Thank you for registering with us, in order to login please use the following:')
                    ->line("E-mail Address: {$notifiable->email}");

        if (! is_null($this->password)) {
            $message->line("Password: {$this->password}");
        }

        return $message;
    }
}
