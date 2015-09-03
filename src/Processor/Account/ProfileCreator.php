<?php namespace Orchestra\Foundation\Processor\Account;

use Exception;
use Orchestra\Support\Str;
use Illuminate\Support\Arr;
use Orchestra\Notifier\Message;
use Illuminate\Support\Facades\DB;
use Orchestra\Model\User as Eloquent;
use Illuminate\Support\Facades\Config;
use Orchestra\Support\Facades\Foundation;
use Illuminate\Contracts\Support\Arrayable;
use Orchestra\Contracts\Foundation\Command\Account\ProfileCreator as Command;
use Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator as Listener;

class ProfileCreator extends User implements Command
{
    /**
     * View registration page.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator  $listener
     *
     * @return mixed
     */
    public function create(Listener $listener)
    {
        $eloquent = Foundation::make('orchestra.user');
        $form     = $this->presenter->profile($eloquent, 'orchestra::register');

        $form->extend(function ($form) {
            $form->submit = 'orchestra/foundation::title.register';
        });

        $this->fireEvent('form', [$eloquent, $form]);

        return $listener->showProfileCreator(compact('eloquent', 'form'));
    }

    /**
     * Create a new user.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator  $listener
     * @param  array  $input
     *
     * @return mixed
     */
    public function store(Listener $listener, array $input)
    {
        $password = Arr::get($input, 'password', Str::random(5));

        $validation = $this->validator->on('register')->with($input);

        // Validate user registration, if any errors is found redirect it
        // back to registration page with the errors
        if ($validation->fails()) {
            return $listener->createProfileFailedValidation($validation->getMessageBag());
        }

        $user = Foundation::make('orchestra.user');

        try {
            $this->saving($user, $input, $password);
        } catch (Exception $e) {
            return $listener->createProfileFailed(['error' => $e->getMessage()]);
        }

        return $this->notifyCreatedUser($listener, $user, $password);
    }

    /**
     * Send new registration e-mail to user.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator  $listener
     * @param  \Orchestra\Model\User  $user
     * @param  string  $password
     *
     * @return mixed
     */
    protected function notifyCreatedUser(Listener $listener, Eloquent $user, $password)
    {
        // Converting the user to an object allow the data to be a generic
        // object. This allow the data to be transferred to JSON if the
        // mail is send using queue.

        $memory = Foundation::memory();
        $site   = $memory->get('site.name', 'Orchestra Platform');

        $data = [
            'password' => $password,
            'site'     => $site,
            'user'     => ($user instanceof Arrayable ? $user->toArray() : $user),
        ];

        $subject = trans('orchestra/foundation::email.credential.register', ['site' => $site]);
        $message = Message::create('emails.auth.register', $data, $subject);

        $receipt = $user->notify($message);

        if ($receipt->failed()) {
            return $listener->profileCreatedWithoutNotification();
        }

        return $listener->profileCreated();
    }

    /**
     * Saving new user.
     *
     * @param  \Orchestra\Model\User  $user
     * @param  array  $input
     * @param  string  $password
     *
     * @return void
     */
    protected function saving(Eloquent $user, array $input, $password)
    {
        $user->setAttribute('email', $input['email']);
        $user->setAttribute('fullname', $input['fullname']);
        $user->setAttribute('password', $password);

        $this->fireEvent('creating', [$user]);
        $this->fireEvent('saving', [$user]);

        DB::transaction(function () use ($user) {
            $user->save();
            $user->roles()->sync([
                Config::get('orchestra/foundation::roles.member', 2),
            ]);
        });

        $this->fireEvent('created', [$user]);
        $this->fireEvent('saved', [$user]);
    }
}
