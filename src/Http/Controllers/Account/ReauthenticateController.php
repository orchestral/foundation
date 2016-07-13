<?php

namespace Orchestra\Foundation\Http\Controllers\Account;

use Illuminate\Support\Facades\Redirect;
use Mpociot\Reauthenticate\ReauthLimiter;
use Orchestra\Foundation\Validation\Account as AccountValidator;

class ReauthenticateController extends Controller
{
    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function setupMiddleware()
    {
        parent::setupMiddleware();

        $this->middleware('orchestra.reauth');
    }

    /**
     * Show reauthenticate page.
     *
     * GET (:orchestra)/sudo
     *
     * @return mixed
     */
    public function show()
    {
        return view('orchestra/foundation::account.reauthenticate');
    }

    /**
     * Handle the reauthentication request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Orchestra\Foundation\Validation\Account  $validator
     *
     * @return mixed
     */
    public function reauth(Request $request, AccountValidator $validator)
    {
        $validation = $validator->on('reauthenticate')->with($request->only(['password']));

        if ($validation->fails()) {
            return $this->userReauthenticateHasFailedValidation($validation->getMessageBag());
        } elseif (! (new ReauthLimiter($request))->attempt($request->input('password'))) {
            return $this->userHasFailedReauthentication();
        }

        return $this->userHasReauthenticated();
    }

    /**
     * Response to user reauthenticate trigger failed validation.
     *
     * @param  \Illuminate\Contracts\Support\MessageBag|array  $errors
     *
     * @return mixed
     */
    public function userReauthenticateHasFailedValidation($errors)
    {
        return $this->redirectWithErrors(handles('orchestra::sudo'), $errors);
    }

    /**
     * Response to user reauthenticate trigger has failed authentication.
     *
     * @return mixed
     */
    protected function userHasFailedReauthentication()
    {
        $message = trans('orchestra/foundation::response.credential.invalid-combination');

        return $this->redirectWithMessage(handles('orchestra::sudo'), $message, 'error');
    }

    /**
     * Response to user reauthenticate successfully.
     *
     * @return mixed
     */
    protected function userHasReauthenticated()
    {
        return Redirect::intended();
    }
}
