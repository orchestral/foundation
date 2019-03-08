<?php

namespace Orchestra\Foundation\Auth;

use Illuminate\Support\Str;
use Laravie\Authen\AuthenUser;
use Orchestra\Model\User as Authenticatable;
use Orchestra\Contracts\Notification\Recipient;
use Orchestra\Foundation\Observers\UserObserver;
use Illuminate\Notifications\RoutesNotifications;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Orchestra\Foundation\Notifications\Welcome as WelcomeNotification;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Orchestra\Foundation\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable implements AuthorizableContract, CanResetPasswordContract, Recipient
{
    use AuthenUser, Authorizable, RoutesNotifications;

    public static function boot()
    {
        parent::boot();

        static::observe(\resolve(UserObserver::class));
    }

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
    public function getRecipientEmail(): string
    {
        return $this->getAttribute('email');
    }

    /**
     * Get the fullname where notification are sent.
     *
     * @return string
     */
    public function getRecipientName(): string
    {
        return $this->getAttribute('fullname');
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return array
     */
    public function getAuthIdentifiersName(): array
    {
        return ['email'];
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
     * @param  string|null  $password
     *
     * @return void
     */
    public function sendWelcomeNotification($password = null)
    {
        $this->notify(new WelcomeNotification($password));
    }

    /**
     * Get the notification routing information for the given driver.
     *
     * @param  string  $driver
     *
     * @return mixed
     */
    public function routeNotificationFor($driver)
    {
        if (\method_exists($this, $method = 'routeNotificationFor'.Str::studly($driver))) {
            return $this->{$method}();
        }

        switch ($driver) {
            case 'database':
                return $this->notifications();
            case 'mail':
                return $this->getRecipientEmail();
            case 'nexmo':
                return $this->getAttribute('phone_number');
        }
    }
}
