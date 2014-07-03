<?php namespace Orchestra\Foundation\Reminders;

use Closure;
use Illuminate\Auth\Reminders\PasswordBroker as Broker;
use Illuminate\Auth\Reminders\ReminderRepositoryInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Support\Contracts\ArrayableInterface;
use Orchestra\Notifier\Message;
use Orchestra\Notifier\NotifierInterface;

class PasswordBroker extends Broker
{
    /**
     * The messages instance.
     *
     * @var \Orchestra\Support\Messages
     */
    protected $messages;

    /**
     * Create a new password broker instance.
     *
     * @param  \Illuminate\Auth\Reminders\ReminderRepositoryInterface  $reminders
     * @param  \Illuminate\Auth\UserProviderInterface                  $users
     * @param  \Orchestra\Notifier\NotifierInterface                   $mailer
     * @param  string                                                  $reminderView
     * @return void
     */
    public function __construct(
        ReminderRepositoryInterface $reminders,
        UserProviderInterface $users,
        NotifierInterface $mailer,
        $reminderView
    ) {
        $this->users = $users;
        $this->mailer = $mailer;
        $this->reminders = $reminders;
        $this->reminderView = $reminderView;
    }

    /**
     * Send a password reminder to a user.
     *
     * @param  array    $credentials
     * @param  Closure  $callback
     * @return string
     */
    public function remind(array $credentials, Closure $callback = null)
    {
        // First we will check to see if we found a user at the given credentials and
        // if we did not we will redirect back to this current URI with a piece of
        // "flash" data in the session to indicate to the developers the errors.
        $user = $this->getUser($credentials);

        if (is_null($user)) {
            return self::INVALID_USER;
        }

        // Once we have the reminder token, we are ready to send a message out to the
        // user with a link to reset their password. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        $token = $this->reminders->create($user);

        $this->sendReminder($user, $token, $callback);

        return self::REMINDER_SENT;
    }

    /**
     * Send the password reminder e-mail.
     *
     * @param  \Illuminate\Auth\Reminders\RemindableInterface  $user
     * @param  string                                          $token
     * @param  Closure                                         $callback
     * @return \Orchestra\Notifier\Receipt
     */
    public function sendReminder(RemindableInterface $user, $token, Closure $callback = null)
    {
        // We will use the reminder view that was given to the broker to display the
        // password reminder e-mail. We'll pass a "token" variable into the views
        // so that it may be displayed for an user to click for password reset.
        $data = array(
            'user'  => ($user instanceof ArrayableInterface ? $user->toArray() : $user),
            'token' => $token,
        );

        $message = Message::create($this->reminderView, $data);

        return $this->mailer->send($user, $message, $callback);
    }
}
