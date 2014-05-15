<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Processor\User as UserProcessor;
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
        return $this->processor->index($this, Input::all());
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
        Site::set('title', trans('orchestra/foundation::title.users.list'));

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
        Site::set('title', trans('orchestra/foundation::title.users.update'));

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
        return $this->redirectWithErrors(handles("orchestra::users/create"), $validation);
    }

    /**
     * Response when storing user failed.
     *
     * @param  array    $error
     * @return Response
     */
    public function storeFailed(array $error)
    {
        $message = trans('orchestra/foundation::response.db-failed', $error);
        return $this->redirectWithMessage(handles('orchestra::users'), $message, 'error');
    }

    /**
     * Response when storing user succeed.
     *
     * @return Response
     */
    public function storeSucceed()
    {
        $message = trans("orchestra/foundation::response.users.create");

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
        return $this->redirectWithErrors(handles("orchestra::users/{$id}/edit"), $validation);
    }

    /**
     * Response when updating user failed.
     *
     * @param  array   $error
     * @return Response
     */
    public function updateFailed(array $error)
    {
        $message = trans('orchestra/foundation::response.db-failed', $error);

        return $this->redirectWithMessage(handles('orchestra::users'), $message, 'error');
    }

    /**
     * Response when updating user succeed.
     *
     * @return Response
     */
    public function updateSucceed()
    {
        $message = trans("orchestra/foundation::response.users.update");

        return $this->redirectWithMessage(handles('orchestra::users'), $message);
    }

    /**
     * Response when destroying user failed.
     *
     * @param  array   $error
     * @return Response
     */
    public function destroyFailed(array $error)
    {
        $message = trans('orchestra/foundation::response.db-failed', $error);

        return $this->redirectWithMessage(handles('orchestra::users'), $message, 'error');
    }

    /**
     * Response when destroying user succeed.
     *
     * @return Response
     */
    public function destroySucceed()
    {
        $message = trans('orchestra/foundation::response.users.delete');

        return $this->redirectWithMessage(handles('orchestra::users'), $message);
    }

    /**
     * Response when user verification failed.
     *
     * @return Response
     */
    public function selfDeletionFailed()
    {
        return $this->suspend(404);
    }

    /**
     * Response when user verification failed.
     *
     * @return Response
     */
    public function userVerificationFailed()
    {
        return $this->suspend(500);
    }
}
