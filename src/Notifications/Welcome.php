<?php

namespace Orchestra\Foundation\Notifications;

use Orchestra\Foundation\Auth\User;
use Orchestra\Notifications\Notification;

class Welcome extends Notification
{
    /**
     * The password.
     *
     * @var string
     */
    public $password;

    /**
     * Create a notification instance.
     *
     * @param  string  $password
     *
     * @return void
     */
    public function __construct($password)
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
        return $this->line('Thank you for registering with us, in order to login please use the following:')
                    ->line("E-mail Address: {$notifiable->email}")
                    ->line("Password: {$this->password}");
    }
}
