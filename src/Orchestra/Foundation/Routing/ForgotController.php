<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Site;

class ForgotController extends AdminController {

	/**
	 * Construct Forgot Password Controller with some pre-define
	 * configuration
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter('orchestra.guest');
		$this->beforeFilter('orchestra.csrf', array('only' => array('postIndex')));
	}

	/**
	 * Show Forgot Password Page where user can enter their current e-mail
	 * address.
	 *
	 * GET (:orchestra)/forgot
	 *
	 * @access public
	 * @return Response
	 */
	public function getIndex()
	{
		Site::set('title', trans('orchestra/foundation::title.forgot-password'));

		return View::make('orchestra/foundation::forgot.index');
	}

	/**
	 * Validate requested e-mail address for password reset, we should first
	 * send a URL where user need to visit before the system can actually
	 * change the password on their behave.
	 *
	 * POST (:orchestra)/forgot
	 *
	 * @access public
	 * @return Response
	 */
	public function postIndex()
	{
		$input      = Input::all();
		$validation = App::make('Orchestra\Foundation\Services\Validation\Auth')->with($input);
		
		if ($validation->fails())
		{
			// If any of the validation is not properly formatted, we need
			// to tell it the the user. This might not be important but a
			// good practice to make sure all form use the same e-mail
			// address validation
			return Redirect::to(handles('orchestra/foundation::forgot'))
					->withInput()
					->withErrors($validation);
		}

		$memory = App::memory();
		$site   = $memory->get('site.name', 'Orchestra Platform');

		return Password::remind(array('email' => $input['email']), function($mail) use ($site)
		{
			$mail->subject(trans('orchestra/foundation::email.forgot.request', compact('site')));
		});
	}

	/**
	 * Once user actually visit the reset my password page, we now should be
	 * able to make the operation to create a new password.
	 *
	 * GET (:orchestra)/forgot/reset/(:hash)
	 *
	 * @access public
	 * @param  string   $token
	 * @return Response
	 */
	public function getReset($token)
	{
		Site::set('title', trans('orchestra/foundation::title.reset-password'));
		
		return View::make('orchestra/foundation::forgot.reset')->with('token', $token);
	}

	/**
	 * Create a new password for the user.
	 *
	 * POST (:orchestra)/forgot/reset/(:hash)
	 *
	 * @access public
	 * @param  string   $token
	 * @return Response
	 */
	public function postReset($token)
	{
		return Password::reset(array('email' => Input::get('email')), function($user, $password)
		{
			$user->password = $password;
			$user->save();

			return Redirect::to(handles('orchestra/foundation::login'));
		});
	}
}
