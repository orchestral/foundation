<?php namespace Orchestra\Foundation\Http\Controllers\Account;

use Illuminate\Support\Facades\Request;
use Orchestra\Contracts\Auth\Listener\PasswordReset;
use Orchestra\Contracts\Auth\Listener\PasswordResetLink;
use Orchestra\Foundation\Http\Controllers\AdminController;
use Orchestra\Foundation\Processor\Account\PasswordBroker as Processor;

class PasswordBrokerController extends AdminController implements PasswordResetLink, PasswordReset
{
    /**
     * Construct Forgot Password Controller with some pre-define
     * configuration.
     *
     * @param \Orchestra\Foundation\Processor\Account\PasswordBroker  $processor
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
        $this->middleware('orchestra.guest');
    }

    /**
     * Show Forgot Password Page where user can enter their current e-mail
     * address.
     *
     * GET (:orchestra)/forgot
     *
     * @return mixed
     */
    public function showLinkRequestForm()
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
    public function sendResetLinkEmail()
    {
        return $this->processor->store($this, Request::all());
    }

    /**
     * Once user actually visit the reset my password page, we now should be
     * able to make the operation to create a new password.
     *
     * GET (:orchestra)/forgot/reset/(:hash)
     *
     * @param  string  $token
     *
     * @return mixed
     */
    public function showResetForm($token = null)
    {
        if (is_null($token)) {
            return $this->showLinkRequestForm();
        }

        $email = Request::input('email');

        set_meta('title', trans('orchestra/foundation::title.reset-password'));

        return view('orchestra/foundation::forgot.reset')->with(compact('email', 'token'));
    }

    /**
     * Create a new password for the user.
     *
     * POST (:orchestra)/forgot/reset
     *
     * @return mixed
     */
    public function reset()
    {
        $input = Request::only('email', 'password', 'password_confirmation', 'token');

        return $this->processor->update($this, $input);
    }

    /**
     * Response when request password failed on validation.
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     *
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
     *
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
     *
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
     *
     * @return mixed
     */
    public function passwordResetHasFailed($response)
    {
        $message = trans($response);
        $token   = Request::input('token');

        return $this->redirectWithMessage(handles("orchestra::forgot/reset/{$token}"), $message, 'error');
    }

    /**
     * Response when reset password succeed.
     *
     * @param  string  $response
     *
     * @return mixed
     */
    public function passwordHasReset($response)
    {
        $message = trans('orchestra/foundation::response.account.password.update');

        return $this->redirectWithMessage(handles('orchestra::/'), $message);
    }
}
