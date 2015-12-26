<?php namespace Orchestra\Foundation\Processor;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Orchestra\Model\User as Eloquent;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Validation\User as Validator;
use Orchestra\Foundation\Http\Presenters\User as Presenter;
use Orchestra\Contracts\Foundation\Command\Account\UserViewer as UserViewerCommand;
use Orchestra\Contracts\Foundation\Command\Account\UserCreator as UserCreatorCommand;
use Orchestra\Contracts\Foundation\Command\Account\UserRemover as UserRemoverCommand;
use Orchestra\Contracts\Foundation\Command\Account\UserUpdater as UserUpdaterCommand;
use Orchestra\Contracts\Foundation\Listener\Account\UserViewer as UserViewerListener;
use Orchestra\Contracts\Foundation\Listener\Account\UserCreator as UserCreatorListener;
use Orchestra\Contracts\Foundation\Listener\Account\UserRemover as UserRemoverListener;
use Orchestra\Contracts\Foundation\Listener\Account\UserUpdater as UserUpdaterListener;

class User extends Processor implements UserCreatorCommand, UserRemoverCommand, UserUpdaterCommand, UserViewerCommand
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Http\Presenters\User  $presenter
     * @param  \Orchestra\Foundation\Validation\User  $validator
     */
    public function __construct(Presenter $presenter, Validator $validator)
    {
        $this->presenter = $presenter;
        $this->validator = $validator;
    }

    /**
     * View list users page.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\UserViewer  $listener
     * @param  array  $input
     *
     * @return mixed
     */
    public function index(UserViewerListener $listener, array $input = [])
    {
        $search = [
            'keyword' => Arr::get($input, 'q', ''),
            'roles'   => Arr::get($input, 'roles', []),
        ];

        // Get Users (with roles) and limit it to only 30 results for
        // pagination. Don't you just love it when pagination simply works.
        $eloquent = Foundation::make('orchestra.user')->search($search['keyword'], $search['roles']);
        $roles    = Foundation::make('orchestra.role')->pluck('name', 'id');

        // Build users table HTML using a schema liked code structure.
        $table = $this->presenter->table($eloquent);

        Event::fire('orchestra.list: users', [$eloquent, $table]);

        // Once all event listening to `orchestra.list: users` is executed,
        // we can add we can now add the final column, edit and delete
        // action for users.
        $this->presenter->actions($table);

        $data = [
            'eloquent' => $eloquent,
            'roles'    => $roles,
            'table'    => $table,
            'search'   => $search,
        ];

        return $listener->showUsers($data);
    }

    /**
     * View create user page.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\UserCreator  $listener
     *
     * @return mixed
     */
    public function create(UserCreatorListener $listener)
    {
        $eloquent = Foundation::make('orchestra.user');
        $form     = $this->presenter->form($eloquent, 'create');

        $this->fireEvent('form', [$eloquent, $form]);

        return $listener->showUserCreator(compact('eloquent', 'form'));
    }

    /**
     * View edit user page.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\UserUpdater  $listener
     * @param  string|int  $id
     *
     * @return mixed
     */
    public function edit(UserUpdaterListener $listener, $id)
    {
        $eloquent = Foundation::make('orchestra.user')->findOrFail($id);
        $form     = $this->presenter->form($eloquent, 'update');

        $this->fireEvent('form', [$eloquent, $form]);

        return $listener->showUserChanger(compact('eloquent', 'form'));
    }

    /**
     * Store a user.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\UserCreator  $listener
     * @param  array   $input
     *
     * @return mixed
     */
    public function store(UserCreatorListener $listener, array $input)
    {
        $validation = $this->validator->on('create')->with($input);

        if ($validation->fails()) {
            return $listener->createUserFailedValidation($validation->getMessageBag());
        }

        $user = Foundation::make('orchestra.user');

        $user->status   = Eloquent::UNVERIFIED;
        $user->password = $input['password'];

        try {
            $this->saving($user, $input, 'create');
        } catch (Exception $e) {
            return $listener->createUserFailed(['error' => $e->getMessage()]);
        }

        return $listener->userCreated();
    }

    /**
     * Update a user.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\UserUpdater  $listener
     * @param  string|int  $id
     * @param  array  $input
     *
     * @return mixed
     */
    public function update(UserUpdaterListener $listener, $id, array $input)
    {
        // Check if provided id is the same as hidden id, just a pre-caution.
        if ((string) $id !== $input['id']) {
            return $listener->abortWhenUserMismatched();
        }

        $validation = $this->validator->on('update')->with($input);

        if ($validation->fails()) {
            return $listener->updateUserFailedValidation($validation->getMessageBag(), $id);
        }

        $user = Foundation::make('orchestra.user')->findOrFail($id);

        ! empty($input['password']) && $user->password = $input['password'];

        try {
            $this->saving($user, $input, 'update');
        } catch (Exception $e) {
            return $listener->updateUserFailed(['error' => $e->getMessage()]);
        }

        return $listener->userUpdated();
    }

    /**
     * Destroy a user.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\UserRemover  $listener
     * @param  string|int  $id
     *
     * @return mixed
     */
    public function destroy(UserRemoverListener $listener, $id)
    {
        $user = Foundation::make('orchestra.user')->findOrFail($id);

        // Avoid self-deleting accident.
        if ((string) $user->id === (string) Auth::user()->id) {
            return $listener->selfDeletionFailed();
        }

        try {
            $this->fireEvent('deleting', [$user]);

            $user->transaction(function () use ($user) {
                $user->delete();
            });

            $this->fireEvent('deleted', [$user]);
        } catch (Exception $e) {
            return $listener->userDeletionFailed(['error' => $e->getMessage()]);
        }

        return $listener->userDeleted();
    }

    /**
     * Save the user.
     *
     * @param  \Orchestra\Model\User  $user
     * @param  array  $input
     * @param  string  $type
     *
     * @return bool
     */
    protected function saving(Eloquent $user, $input = [], $type = 'create')
    {
        $beforeEvent = ($type === 'create' ? 'creating' : 'updating');
        $afterEvent  = ($type === 'create' ? 'created' : 'updated');

        $user->fullname = $input['fullname'];
        $user->email    = $input['email'];

        $this->fireEvent($beforeEvent, [$user]);
        $this->fireEvent('saving', [$user]);

        $user->transaction(function () use ($user, $input) {
            $user->save();
            $user->roles()->sync($input['roles']);
        });

        $this->fireEvent($afterEvent, [$user]);
        $this->fireEvent('saved', [$user]);

        return true;
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
        Event::fire("orchestra.{$type}: users", $parameters);
        Event::fire("orchestra.{$type}: user.account", $parameters);
    }
}
