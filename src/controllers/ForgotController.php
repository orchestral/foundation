<?php namespace Orchestra\Foundation;

use Input;
use Redirect;
use Str;
use View;
use Orchestra\App;
use Orchestra\Mail;
use Orchestra\Site;
use Orchestra\Model\User;

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

		$this->beforeFilter('orchestra.logged');
		//$this->beforeFilter('orchestra.csrf', array('only' => array('postIndex')));
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
		$validation = App::make('Orchestra\Services\Validation\Auth')
						->on('login')->with($input);
		
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

		$user = User::where('email', '=', $input['email'])->first();

		if (is_null($user))
		{
			var_dump('foo');die();
			// no user could be associated with the provided email address
			Messages::add('error', trans('orchestra/foundation::response.db-404'));

			return Redirect::to(handles('orchestra/foundation::forgot'));
		}

		var_dump($user);die();

		$meta   = App::make('orchestra.memory')->driver('user');
		$memory = App::memory();
		$hash   = sha1($user->email.Str::random(10));
		$url    = handles("orchestra/foundation::forgot/reset/{$user->id}/{$hash}");
		$site   = $memory->get('site.name', 'Orchestra');
		$data   = compact('user', 'url', 'site');
		
		Mail::send('orchestra/foundation::email.forgot.request', $data, function ($m) use ($site, $user)
		{
			$m->subject(trans('orchestra/foundation::email.forgot.request', compact('site')));
			$m->to($user->email, $user->fullname);
		});
		
		// Messages::add('error', trans('orchestra/foundation::response.forgot.email-fail'));
		
		$meta->put("reset_password_hash.{$user->id}", $hash);
		Messages::add('success', trans('orchestra/foundation::response.forgot.email-send'));

		return Redirect::to(handles('orchestra/foundation::forgot'));
	}
}
