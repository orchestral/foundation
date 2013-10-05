<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Site;
use Orchestra\Foundation\Validation\Auth as AuthValidator;

class ForgotController extends AdminController {

	/**
	 * Construct Forgot Password Controller with some pre-define
	 * configuration
	 * 
	 * @param \Orchestra\Foundation\Validation\Auth $validator
	 */
	public function __construct(AuthValidator $validator)
	{
		$this->validator = $validator;

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
		$this->beforeFilter('orchestra.csrf', array('only' => array('postIndex')));
	}

	/**
	 * Show Forgot Password Page where user can enter their current e-mail
	 * address.
	 *
	 * GET (:orchestra)/forgot
	 *
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
	 * @return Response
	 */
	public function postIndex()
	{
		$input      = Input::all();
		$validation = $this->validator->with($input);
		
		if ($validation->fails())
		{
			// If any of the validation is not properly formatted, we need
			// to tell it the the user. This might not be important but a
			// good practice to make sure all form use the same e-mail
			// address validation
			return Redirect::to(handles('orchestra::forgot'))
					->withInput()
					->withErrors($validation);
		}

		$memory   = App::memory();
		$site     = $memory->get('site.name', 'Orchestra Platform');
		$callback = function($mail) use ($site)
		{
			$mail->subject(trans('orchestra/foundation::email.forgot.request', array('site' => $site)));
		};

		return Password::remind(array('email' => $input['email']), $callback);
	}

	/**
	 * Once user actually visit the reset my password page, we now should be
	 * able to make the operation to create a new password.
	 *
	 * GET (:orchestra)/forgot/reset/(:hash)
	 *
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
	 * @return Response
	 */
	public function postReset()
	{
		return Password::reset(array('email' => Input::get('email')), function($user, $password)
		{
			$user->password = $password;
			$user->save();

			Messages::add('success', trans('orchestra/foundation::response.account.password.update'));

			return Redirect::to(handles('orchestra::login'));
		});
	}
}
