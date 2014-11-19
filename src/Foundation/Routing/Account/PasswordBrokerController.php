<?php namespace Orchestra\Foundation\Routing\Account;

use Illuminate\Support\Facades\Input;
use Orchestra\Foundation\Contracts\Listener\Account\PasswordReset;
use Orchestra\Foundation\Routing\AdminController;
use Orchestra\Foundation\Contracts\Listener\Account\PasswordResetLink;
use Orchestra\Foundation\Processor\Account\PasswordBroker as Processor;

class PasswordBrokerController extends AdminController implements PasswordResetLink, PasswordReset
{
    /**
     * Construct Forgot Password Controller with some pre-define
     * configuration
     *
     * @param \Orchestra\Foundation\Processor\Account\PasswordBroker  $processor
     */
    public function __construct(Processor $processor)
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
        $this->beforeFilter('orchestra.guest');
    }

    /**
     * Show Forgot Password Page where user can enter their current e-mail
     * address.
     *
     * GET (:orchestra)/forgot
     *
     * @return mixed
     */
    public function create()
    {
        set_meta('title', trans('orchestra/foundation::title.forgot-password'));

        return view('orchestra/foundation::forgot.index');
    }

    /**
     * Validate requested e-mail address for password reset, we should first
     * send a URL where user need to visit before the system can actually
     * change the password on their behave.
     *
     * POST (:orchestra)/forgot
     *
     * @return mixed
     */
    public function store()
    {
        return $this->processor->store($this, Input::all());
    }

    /**
     * Once user actually visit the reset my password page, we now should be
     * able to make the operation to create a new password.
     *
     * GET (:orchestra)/forgot/reset/(:hash)
     *
     * @param  string  $token
     * @return mixed
     */
    public function show($token)
    {
        set_meta('title', trans('orchestra/foundation::title.reset-password'));

        return view('orchestra/foundation::forgot.reset')->with('token', $token);
    }

    /**
     * Create a new password for the user.
     *
     * POST (:orchestra)/forgot/reset
     *
     * @return mixed
     */
    public function update()
    {
        $input = Input::only('email', 'password', 'password_confirmation', 'token');

        return $this->processor->update($this, $input);
    }

    /**
     * Response when request password failed on validation.
     *
     * @param  mixed  $errors
     * @return mixed
     */
    public function resetLinkFailedValidation($errors)
    {
        // If any of the validation is not properly formatted, we need
        // to tell it the the user. This might not be important but a
        // good practice to make sure all form use the same e-mail
        // address validation
        return $this->redirectWithErrors(handles('orchestra::forgot'), $errors);
    }

    /**
     * Response when request reset password failed.
     *
     * @param  string  $response
     * @return mixed
     */
    public function resetLinkFailed($response)
    {
        $message = trans($response);

        return $this->redirectWithMessage(handles('orchestra::forgot'), $message, 'error');
    }

    /**
     * Response when request reset password succeed.
     *
     * @param  string  $response
     * @return mixed
     */
    public function resetLinkSent($response)
    {
        $message = trans($response);

        return $this->redirectWithMessage(handles('orchestra::forgot'), $message);
    }

    /**
     * Response when reset password failed.
     *
     * @param  string  $response
     * @return mixed
     */
    public function passwordResetHasFailed($response)
    {
        $message = trans($response);
        $token   = Input::get('token');

        return $this->redirectWithMessage(handles("orchestra::forgot/reset/{$token}"), $message, 'error');
    }

    /**
     * Response when reset password succeed.
     *
     * @return mixed
     */
    public function passwordHasReset()
    {
        $message = trans('orchestra/foundation::response.account.password.update');

        return $this->redirectWithMessage(handles('orchestra::/'), $message);
    }
}
