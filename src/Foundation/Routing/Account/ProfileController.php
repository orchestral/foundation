<?php namespace Orchestra\Foundation\Routing\Account;

use Illuminate\Support\Facades\Input;
use Orchestra\Foundation\Processor\Account\ProfileUpdater as Processor;
use Orchestra\Foundation\Contracts\Listener\Account\ProfileUpdater as Listener;

class ProfileController extends Controller implements Listener
{
    /**
     * Construct Account Controller to allow user to update own profile.
     * Only authenticated user should be able to access this controller.
     *
     * @param  \Orchestra\Foundation\Processor\Account\ProfileUpdater  $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }

    /**
     * Edit user account/profile page.
     *
     * GET (:orchestra)/account
     *
     * @return mixed
     */
    public function show()
    {
        return $this->processor->show($this);
    }

    /**
     * POST Edit user account/profile.
     *
     * POST (:orchestra)/account
     *
     * @return mixed
     */
    public function update()
    {
        return $this->processor->update($this, Input::all());
    }

    /**
     * Response to show user profile changer.
     *
     * @param  array  $data
     * @return mixed
     */
    public function showProfileChanger(array $data)
    {
        set_meta('title', trans("orchestra/foundation::title.account.profile"));

        return view('orchestra/foundation::account.index', $data);
    }

    /**
     * Response when validation on update profile failed.
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     * @return mixed
     */
    public function updateProfileFailedValidation($errors)
    {
        return $this->redirectWithErrors(handles('orchestra::account'), $errors);
    }

    /**
     * Response when update profile failed.
     *
     * @param  array  $errors
     * @return mixed
     */
    public function updateProfileFailed(array $errors)
    {
        $message = trans('orchestra/foundation::response.db-failed', $errors);

        return $this->redirectWithMessage(handles('orchestra::account'), $message, 'error');
    }

    /**
     * Response when update profile succeed.
     *
     * @return mixed
     */
    public function profileUpdated()
    {
        $message = trans('orchestra/foundation::response.account.profile.update');

        return $this->redirectWithMessage(handles('orchestra::account'), $message);
    }
}
