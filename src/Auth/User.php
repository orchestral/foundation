<?php

namespace Orchestra\Foundation\Auth;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\RoutesNotifications;
use Illuminate\Support\Str;
use Laravie\Authen\AuthenUser;
use Orchestra\Contracts\Notification\Recipient;
use Orchestra\Foundation\Notifications\ResetPassword as ResetPasswordNotification;
use Orchestra\Foundation\Notifications\Welcome as WelcomeNotification;
use Orchestra\Foundation\Observers\UserObserver;
use Orchestra\Model\User as Authenticatable;

class User extends Authenticatable implements AuthorizableContract, CanResetPasswordContract, Recipient
{
    use AuthenUser, Authorizable, RoutesNotifications;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
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
