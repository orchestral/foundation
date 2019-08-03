<?php

namespace Orchestra\Foundation\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Orchestra\Foundation\Processors\User as Processor;
use Orchestra\Contracts\Foundation\Listener\Account\UserViewer;
use Orchestra\Contracts\Foundation\Listener\Account\UserCreator;
use Orchestra\Contracts\Foundation\Listener\Account\UserRemover;
use Orchestra\Contracts\Foundation\Listener\Account\UserUpdater;

class UsersController extends AdminController implements UserCreator, UserRemover, UserUpdater, UserViewer
{
    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function onCreate()
    {
        $this->middleware([
            'orchestra.auth',
            'orchestra.can:manage-users',
        ]);
        $this->middleware('orchestra.csrf', ['only' => 'delete']);
    }

    /**
     * List all the users.
     *
     * GET (:orchestra)/users
     *
     * @param  \Orchestra\Foundation\Processors\User  $processor
     *
     * @return mixed
     */
    public function index(Processor $processor)
    {
        return $processor->view($this, Input::all());
    }

    /**
     * Create a new user.
     *
     * GET (:orchestra)/users/create
     *
     * @param  \Orchestra\Foundation\Processors\User  $processor
     *
     * @return mixed
     */
    public function create(Processor $processor)
    {
        return $processor->create($this);
    }

    /**
     * Edit the user.
     *
     * GET (:orchestra)/users/$user/edit
     *
     * @param  \Orchestra\Foundation\Processors\User  $processor
     * @param  int|string  $user
     *
     * @return mixed
     */
    public function edit(Processor $processor, $user)
    {
        return $processor->edit($this, $user);
    }

    /**
     * Create the user.
     *
     * POST (:orchestra)/users
     *
     * @param  \Orchestra\Foundation\Processors\User  $processor
     *
     * @return mixed
     */
    public function store(Processor $processor)
    {
        return $processor->store($this, Input::all());
    }

    /**
     * Update the user.
     *
     * PUT (:orchestra)/users/$user
     *
     * @param  \Orchestra\Foundation\Processors\User  $processor
     * @param  int|string  $user
     *
     * @return mixed
     */
    public function update(Processor $processor, $user)
    {
        return $processor->update($this, $user, Input::all());
    }

    /**
     * Request to delete a user.
     *
     * GET (:orchestra)/$user/delete
     *
     * @param  \Orchestra\Foundation\Processors\User  $processor
     * @param  int|string  $user
     *
     * @return mixed
     */
    public function delete(Processor $processor, $user)
    {
        return $this->destroy($processor, $user);
    }

    /**
     * Request to delete a user.
     *
     * DELETE (:orchestra)/$user
     *
     * @param  \Orchestra\Foundation\Processors\User  $processor
     * @param  int|string  $user
     *
     * @return mixed
     */
    public function destroy(Processor $processor, $user)
    {
        return $processor->destroy($this, $user);
    }

    /**
     * Response when list users page succeed.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function showUsers(array $data)
    {
        \set_meta('title', \trans('orchestra/foundation::title.users.list'));

        return \view('orchestra/foundation::users.index', $data);
    }

    /**
     * Response when create user page succeed.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function showUserCreator(array $data)
    {
        \set_meta('title', \trans('orchestra/foundation::title.users.create'));

        return \view('orchestra/foundation::users.edit', $data);
    }

    /**
     * Response when edit user page succeed.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function showUserChanger(array $data)
    {
        \set_meta('title', \trans('orchestra/foundation::title.users.update'));

        return \view('orchestra/foundation::users.edit', $data);
    }

    /**
     * Response when storing user failed on validation.
     *
     * @param  \Illuminate\Contracts\Support\MessageBag|array  $errors
     *
     * @return mixed
     */
    public function createUserFailedValidation($errors)
    {
        return $this->redirectWithErrors(handles('orchestra::users/create'), $errors);
    }

    /**
     * Response when storing user failed.
     *
     * @param  array  $errors
     *
     * @return mixed
     */
    public function createUserFailed(array $errors)
    {
        $message = \trans('orchestra/foundation::response.db-failed', $errors);

        return $this->redirectWithMessage(\handles('orchestra::users'), $message, 'error');
    }

    /**
     * Response when storing user succeed.
     *
     * @return mixed
     */
    public function userCreated()
    {
        $message = \trans('orchestra/foundation::response.users.create');

        return $this->redirectWithMessage(\handles('orchestra::users'), $message);
    }

    /**
     * Response when update user failed on validation.
     *
     * @param  \Illuminate\Contracts\Support\MessageBag|array  $errors
     * @param  string|int  $id
     *
     * @return mixed
     */
    public function updateUserFailedValidation($errors, $id)
    {
        return $this->redirectWithErrors(\handles("orchestra::users/{$id}/edit"), $errors);
    }

    /**
     * Response when updating user failed.
     *
     * @param  array  $errors
     *
     * @return mixed
     */
    public function updateUserFailed(array $errors)
    {
        $message = \trans('orchestra/foundation::response.db-failed', $errors);

        return $this->redirectWithMessage(\handles('orchestra::users'), $message, 'error');
    }

    /**
     * Response when updating user succeed.
     *
     * @return mixed
     */
    public function userUpdated()
    {
        $message = \trans('orchestra/foundation::response.users.update');

        return $this->redirectWithMessage(\handles('orchestra::users'), $message);
    }

    /**
     * Response when destroying user failed.
     *
     * @param  array  $errors
     *
     * @return mixed
     */
    public function userDeletionFailed(array $errors)
    {
        $message = \trans('orchestra/foundation::response.db-failed', $errors);

        return $this->redirectWithMessage(\handles('orchestra::users'), $message, 'error');
    }

    /**
     * Response when destroying user succeed.
     *
     * @return mixed
     */
    public function userDeleted()
    {
        $message = \trans('orchestra/foundation::response.users.delete');

        return $this->redirectWithMessage(\handles('orchestra::users'), $message);
    }

    /**
     * Response when user tried to self delete.
     *
     * @return mixed
     */
    public function selfDeletionFailed()
    {
        return $this->suspend(404);
    }

    /**
     * Response when user verification failed.
     *
     * @return mixed
     */
    public function abortWhenUserMismatched()
    {
        return $this->suspend(500);
    }
}
