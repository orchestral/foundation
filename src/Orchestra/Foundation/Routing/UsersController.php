<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Processor\User as UserProcessor;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Site;

class UsersController extends AdminController
{
    /**
     * CRUD Controller for Users management using resource routing.
     *
     * @param  \Orchestra\Foundation\Processor\User    $processor
     * @param  \Orchestra\Foundation\Validation\User    $validator
     */
    public function __construct(UserProcessor $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }

    /**
     * Setup controller filters.
     *
     * @return void
     */
    protected function setupFilters()
    {
        $this->beforeFilter('orchestra.auth');
        $this->beforeFilter('orchestra.manage:users');
    }

    /**
     * List all the users.
     *
     * GET (:orchestra)/users
     *
     * @return Response
     */
    public function index()
    {
        Site::set('title', trans('orchestra/foundation::title.users.list'));

        return $this->processor->index($this, Input::get('q', ''), Input::get('roles', array()));
    }

    /**
     * Create a new user.
     *
     * GET (:orchestra)/users/create
     *
     * @return Response
     */
    public function create()
    {
        Site::set('title', trans('orchestra/foundation::title.users.create'));

        return $this->processor->create($this);
    }

    /**
     * Edit the user.
     *
     * GET (:orchestra)/users/$id/edit
     *
     * @return Response
     */
    public function edit($id)
    {
        Site::set('title', trans('orchestra/foundation::title.users.update'));

        return $this->processor->edit($this, $id);
    }

    /**
     * Create the user.
     *
     * POST (:orchestra)/users
     *
     * @return Response
     */
    public function store()
    {
        return $this->processor->store($this, Input::all());
    }

    /**
     * Update the user.
     *
     * PUT (:orchestra)/users/1
     *
     * @param  integer  $id
     * @return Response
     */
    public function update($id)
    {
        return $this->processor->update($this, $id, Input::all());
    }

    /**
     * Request to delete a user.
     *
     * GET (:orchestra)/$id/delete
     *
     * @param  integer  $id
     * @return Response
     */
    public function delete($id)
    {
        return $this->destroy($id);
    }

    /**
     * Request to delete a user.
     *
     * DELETE (:orchestra)/$id
     *
     * @param  integer  $id
     * @return Response
     */
    public function destroy($id)
    {
        return $this->processor->destroy($this, $id);
    }

    /**
     * Response when list users page succeed.
     *
     * @param  array  $data
     * @return Response
     */
    public function indexSucceed(array $data)
    {
        return View::make('orchestra/foundation::users.index', $data);
    }

    /**
     * Response when create user page succeed.
     *
     * @param  array  $data
     * @return Response
     */
    public function createSucceed(array $data)
    {
        return View::make('orchestra/foundation::users.edit', $data);
    }

    /**
     * Response when edit user page succeed.
     *
     * @param  array  $data
     * @return Response
     */
    public function editSucceed(array $data)
    {
        return View::make('orchestra/foundation::users.edit', $data);
    }

    /**
     * Response when storing user failed on validation.
     *
     * @param  object  $validation
     * @return Response
     */
    public function storeValidationFailed($validation)
    {
        return Redirect::to(handles("orchestra::users/create"))
                ->withInput()
                ->withErrors($validation);
    }

    /**
     * Response when storing user failed.
     *
     * @param  string  $message
     * @return Response
     */
    public function storeFailed($message)
    {
        return $this->redirectWithMessage(handles('orchestra::users'), $message, 'error');
    }

    /**
     * Response when storing user succeed.
     *
     * @param  string  $message
     * @return Response
     */
    public function storeSucceed($message)
    {
        return $this->redirectWithMessage(handles('orchestra::users'), $message);
    }

    /**
     * Response when update user failed on validation.
     *
     * @param  object  $validation
     * @return Response
     */
    public function updateValidationFailed($validation, $id)
    {
        return Redirect::to(handles("orchestra::users/{$id}/edit"))
                ->withInput()
                ->withErrors($validation);
    }

    /**
     * Response when updating user failed.
     *
     * @param  string  $message
     * @return Response
     */
    public function updateFailed($message)
    {
        return $this->redirectWithMessage(handles('orchestra::users'), $message, 'error');
    }

    /**
     * Response when updating user succeed.
     *
     * @param  string  $message
     * @return Response
     */
    public function updateSucceed($message)
    {
        return $this->redirectWithMessage(handles('orchestra::users'), $message);
    }

    /**
     * Response when destroying user failed.
     *
     * @param  string  $message
     * @return Response
     */
    public function destroyFailed($message)
    {
        return $this->redirectWithMessage(handles('orchestra::users'), $message, 'error');
    }

    /**
     * Response when destroying user succeed.
     *
     * @param  string  $message
     * @return Response
     */
    public function destroySucceed($message)
    {
        return $this->redirectWithMessage(handles('orchestra::users'), $message);
    }

    /**
     * Response when user verification failed.
     *
     * @return Response
     */
    public function selfDeletionFailed()
    {
        return App::abort(404);
    }

    /**
     * Response when user verification failed.
     *
     * @return Response
     */
    public function userVerificationFailed()
    {
        return App::abort(500);
    }
}
