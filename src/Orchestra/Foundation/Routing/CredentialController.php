<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Site;
use Orchestra\Foundation\Processor\Credential as CredentialProcessor;

class CredentialController extends AdminController
{
    /**
     * Authentication/Credential controller routing.
     *
     * @param \Orchestra\Foundation\Processor\Credential   $processor
     */
    public function __construct(CredentialProcessor $processor)
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
        $this->beforeFilter('orchestra.guest', array(
            'only' => array(
                'getLogin', 'postLogin',
                'getRegister', 'postRegister',
            ),
        ));

        $this->beforeFilter('orchestra.registrable', array(
            'only' => array(
                'getRegister', 'postRegister',
            ),
        ));

        $this->beforeFilter('orchestra.csrf', array('only' => array('postLogin')));
    }

    /**
     * Login page.
     *
     * GET (:orchestra)/login
     *
     * @return Response
     */
    public function getLogin()
    {
        Site::set('title', trans("orchestra/foundation::title.login"));

        return View::make('orchestra/foundation::credential.login');
    }

    /**
     * POST Login the user.
     *
     * POST (:orchestra)/login
     *
     * @return Response
     */
    public function postLogin()
    {
        return $this->processor->login($this, Input::all());
    }

    /**
     * Logout the user.
     *
     * DELETE (:bundle)/login
     *
     * @return Response
     */
    public function deleteLogin()
    {
        return $this->processor->logout($this);
    }

    /**
     * Response when validation on login failed.
     *
     * @param  object  $validation
     * @return Response
     */
    public function loginValidationFailed($validation)
    {
        return Redirect::to(handles('orchestra::login'))->withInput()->withErrors($validation);
    }

    /**
     * Response when login failed.
     *
     * @param  string  $message
     * @return Response
     */
    public function loginFailed($message)
    {
        Messages::add('error', $message);

        return Redirect::to(handles('orchestra::login'))->withInput();
    }

    /**
     * Response when login succeed.
     *
     * @param  string  $message
     * @return Response
     */
    public function loginSucceed($message)
    {
        Messages::add('success', $message);

        return Redirect::intended(handles('orchestra::/'));
    }

    /**
     * Response when logout succeed.
     *
     * @param  string  $message
     * @return Response
     */
    public function logoutSucceed($message)
    {
        Messages::add('success', $message);

        return Redirect::intended(handles(Input::get('redirect', 'orchestra::login')));
    }
}
