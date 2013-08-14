<?php namespace Orchestra\Foundation\Reminders;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Auth\Reminders\PasswordBroker as Broker;
use Illuminate\Auth\Reminders\ReminderRepositoryInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserProviderInterface;
use Orchestra\Foundation\Mail as Mailer;
use Orchestra\Support\Messages;

class PasswordBroker extends Broker {

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
	 * @param  \Illuminate\Auth\UserProviderInterface  $users
	 * @param  \Illuminate\Routing\Redirector  $redirect
	 * @param  \Orchestra\Foundation\Mail  $mailer
	 * @param  string  $reminderView
	 * @return void
	 */
	public function __construct(ReminderRepositoryInterface $reminders,
                                UserProviderInterface $users,
                                Redirector $redirect,
                                Mailer $mailer,
                                $reminderView)
	{
		$this->users = $users;
		$this->mailer = $mailer;
		$this->redirect = $redirect;
		$this->reminders = $reminders;
		$this->reminderView = $reminderView;
	}

	/**
	 * Set MessageBag instance.
	 *
	 * @param  \Orchestra\Support\Messages  $messages
	 * @return self
	 */
	public function setMessageBag($messages)
	{
		$this->messages = $messages;

		return $this;
	}

	/**
	 * Get MessageBag instance.
	 *
	 * @return \Orchestra\Support\Messages
	 */
	public function getMessageBag()
	{
		return $this->messages;
	}

	/**
	 * Send a password reminder to a user.
	 *
	 * @param  array    $credentials
	 * @param  Closure  $callback
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function remind(array $credentials, Closure $callback = null)
	{
		// First we will check to see if we found a user at the given credentials and
		// if we did not we will redirect back to this current URI with a piece of
		// "flash" data in the session to indicate to the developers the errors.
		$user = $this->getUser($credentials);

		if (is_null($user))
		{
			return $this->makeErrorRedirect('user');
		}

		// Once we have the reminder token, we are ready to send a message out to the
		// user with a link to reset their password. We will then redirect back to
		// the current URI having nothing set in the session to indicate errors.
		$token = $this->reminders->create($user);

		$this->sendReminder($user, $token, $callback);

		$this->messages->add('success', trans('orchestra/foundation::response.reminders.email-send'));

		return $this->redirect->refresh();
	}

	/**
	 * Make an error redirect response.
	 *
	 * @param  string  $reason
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function makeErrorRedirect($reason = '')
	{
		if ($reason != '') $reason = 'reminders.'.$reason;

		$this->messages->add('error', trans($reason));

		return $this->redirect->refresh();
	}

}
