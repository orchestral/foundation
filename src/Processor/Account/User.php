<?php namespace Orchestra\Foundation\Processor\Account;

use Illuminate\Support\Facades\Event;
use Orchestra\Foundation\Processor\Processor;
use Orchestra\Foundation\Validation\Account as Validator;
use Orchestra\Foundation\Http\Presenters\Account as Presenter;

abstract class User extends Processor
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Http\Presenters\Account  $presenter
     * @param  \Orchestra\Foundation\Validation\Account  $validator
     */
    public function __construct(Presenter $presenter, Validator $validator)
    {
        $this->presenter = $presenter;
        $this->validator = $validator;
    }

    /**
     * Validate current user.
     *
     * @param  \Orchestra\Model\User|\Illuminate\Database\Eloquent\Model  $user
     * @param  array  $input
     *
     * @return bool
     */
    protected function validateCurrentUser($user, array $input)
    {
        return (string) $user->getAttribute('id') === $input['id'];
    }

    /**
     * Fire Event related to eloquent process.
     *
     * @param  string  $type
     * @param  array   $parameters
     *
     * @return void
     */
    protected function fireEvent($type, array $parameters = [])
    {
        Event::fire("orchestra.{$type}: user.account", $parameters);
    }
}
