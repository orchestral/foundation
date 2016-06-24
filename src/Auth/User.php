<?php

namespace Orchestra\Foundation\Auth;

use Orchestra\Model\User as Authenticatable;
use Orchestra\Contracts\Notification\Recipient;
use Illuminate\Notifications\RoutesNotifications;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Orchestra\Foundation\Notifications\Welcome as WelcomeNotification;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Orchestra\Foundation\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable implements AuthorizableContract, CanResetPasswordContract, Recipient
{
    use Authorizable, RoutesNotifications;

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->getRecipientEmail();
    }

    /**
     * Get the e-mail address where notification are sent.
     *
     * @return string
     */
    public function getRecipientEmail()
    {
        return $this->getAttribute('email');
    }

    /**
     * Get the fullname where notification are sent.
     *
     * @return string
     */
    public function getRecipientName()
    {
        return $this->getAttribute('fullname');
    }

    /**
     * Get the notification routing information for mail driver.
     *
     * @param  string  $driver
     *
     * @return mixed
     */
    public function routeNotificationForMail()
    {
        return $this->getRecipientEmail();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @param  string|null  $provider
     *
     * @return void
     */
    public function sendPasswordResetNotification($token, $provider = null)
    {
        $this->notify(new ResetPasswordNotification($token, $provider));
    }

    /**
     * Send the user registered notification.
     *
     * @param  string  $password
     *
     * @return void
     */
    public function sendWelcomeNotification($password)
    {
        $this->notify(new WelcomeNotification($password));
    }
}
