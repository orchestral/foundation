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
            'only' => array('index', 'create'),
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
    public function index()
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
    public function login()
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
    public function logout()
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
        return $this->redirectWithErrors(handles('orchestra::login'), $validation);
    }

    /**
     * Response when login failed.
     *
     * @param  string|null $message
     * @return Response
     */
    public function loginFailed($message = null)
    {
        return $this->redirectWithMessage(handles('orchestra::login'), $message, 'error')->withInput();
    }

    /**
     * Response when login succeed.
     *
     * @param  string|null $message
     * @return Response
     */
    public function loginSucceed($message = null)
    {
        Messages::add('success', $message);

        return Redirect::intended(handles('orchestra::/'));
    }

    /**
     * Response when logout succeed.
     *
     * @param  string|null $message
     * @return Response
     */
    public function logoutSucceed($message = null)
    {
        Messages::add('success', $message);

        return Redirect::intended(handles(Input::get('redirect', 'orchestra::login')));
    }
}
