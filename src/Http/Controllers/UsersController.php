<?php

namespace Orchestra\Foundation\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Orchestra\Foundation\Processor\User as Processor;
use Orchestra\Contracts\Foundation\Listener\Account\UserViewer;
use Orchestra\Contracts\Foundation\Listener\Account\UserCreator;
use Orchestra\Contracts\Foundation\Listener\Account\UserRemover;
use Orchestra\Contracts\Foundation\Listener\Account\UserUpdater;

class UsersController extends AdminController implements UserCreator, UserRemover, UserUpdater, UserViewer
{
    /**
     * CRUD Controller for Users management using resource routing.
     *
     * @param  \Orchestra\Foundation\Processor\User  $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }

    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function setupMiddleware()
    {
        $this->middleware('orchestra.auth');
        $this->middleware('orchestra.can:manage-users');
        $this->middleware('orchestra.csrf', ['only' => 'delete']);
    }

    /**
     * List all the users.
     *
     * GET (:orchestra)/users
     *
     * @return mixed
     */
    public function index()
    {
        return $this->processor->index($this, Input::all());
    }

    /**
     * Create a new user.
     *
     * GET (:orchestra)/users/create
     *
     * @return mixed
     */
    public function create()
    {
        return $this->processor->create($this);
    }

    /**
     * Edit the user.
     *
     * GET (:orchestra)/users/$users/edit
     *
     * @param  int|string  $users
     *
     * @return mixed
     */
    public function edit($users)
    {
        return $this->processor->edit($this, $users);
    }

    /**
     * Create the user.
     *
     * POST (:orchestra)/users
     *
     * @return mixed
     */
    public function store()
    {
        return $this->processor->store($this, Input::all());
    }

    /**
     * Update the user.
     *
     * PUT (:orchestra)/users/$users
     *
     * @param  int|string  $users
     *
     * @return mixed
     */
    public function update($users)
    {
        return $this->processor->update($this, $users, Input::all());
    }

    /**
     * Request to delete a user.
     *
     * GET (:orchestra)/$users/delete
     *
     * @param  int|string  $users
     *
     * @return mixed
     */
    public function delete($users)
    {
        return $this->destroy($users);
    }

    /**
     * Request to delete a user.
     *
     * DELETE (:orchestra)/$users
     *
     * @param  int|string  $users
     *
     * @return mixed
     */
    public function destroy($users)
    {
        return $this->processor->destroy($this, $users);
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
        set_meta('title', trans('orchestra/foundation::title.users.list'));

        return view('orchestra/foundation::users.index', $data);
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
        set_meta('title', trans('orchestra/foundation::title.users.create'));

        return view('orchestra/foundation::users.edit', $data);
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
        set_meta('title', trans('orchestra/foundation::title.users.update'));

        return view('orchestra/foundation::users.edit', $data);
    }

    /**
     * Response when storing user failed on validation.
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
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
        $message = trans('orchestra/foundation::response.db-failed', $errors);

        return $this->redirectWithMessage(handles('orchestra::users'), $message, 'error');
    }

    /**
     * Response when storing user succeed.
     *
     * @return mixed
     */
    public function userCreated()
    {
        $message = trans('orchestra/foundation::response.users.create');

        return $this->redirectWithMessage(handles('orchestra::users'), $message);
    }

    /**
     * Response when update user failed on validation.
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     * @param  string|int  $id
     *
     * @return mixed
     */
    public function updateUserFailedValidation($errors, $id)
    {
        return $this->redirectWithErrors(handles("orchestra::users/{$id}/edit"), $errors);
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
        $message = trans('orchestra/foundation::response.db-failed', $errors);

        return $this->redirectWithMessage(handles('orchestra::users'), $message, 'error');
    }

    /**
     * Response when updating user succeed.
     *
     * @return mixed
     */
    public function userUpdated()
    {
        $message = trans('orchestra/foundation::response.users.update');

        return $this->redirectWithMessage(handles('orchestra::users'), $message);
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
        $message = trans('orchestra/foundation::response.db-failed', $errors);

        return $this->redirectWithMessage(handles('orchestra::users'), $message, 'error');
    }

    /**
     * Response when destroying user succeed.
     *
     * @return mixed
     */
    public function userDeleted()
    {
        $message = trans('orchestra/foundation::response.users.delete');

        return $this->redirectWithMessage(handles('orchestra::users'), $message);
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
