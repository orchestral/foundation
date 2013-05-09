<?php namespace Orchestra\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Mail;
use Orchestra\Support\Facades\Memory;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Site;
use Orchestra\Support\Str;
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
		$validation = App::make('Orchestra\Services\Validation\Auth')->with($input);
		
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

		$user     = User::where('email', '=', $input['email'])->first();
		$userMeta = Memory::make('user');

		if (is_null($user))
		{
			// no user could be associated with the provided email address
			Messages::add('error', trans('orchestra/foundation::response.db-404'));

			return Redirect::to(handles('orchestra/foundation::forgot'));
		}

		$memory = App::memory();
		$hash   = sha1($user->email.Str::random(10));
		$url    = handles("orchestra/foundation::forgot/reset/{$user->id}/{$hash}");
		$site   = $memory->get('site.name', 'Orchestra Platform');
		$data   = compact('user', 'url', 'site');
		
		$sent = Mail::send('orchestra/foundation::email.forgot.request', $data, function ($m) 
			use ($site, $user)
		{
			$m->subject(trans('orchestra/foundation::email.forgot.request', compact('site')));
			$m->to($user->email, $user->fullname);
		});

		if ($sent < 1)
		{
			Messages::add('error', trans('orchestra/foundation::response.forgot.email-fail'));
		}
		else
		{
			$userMeta->put("reset_password_hash.{$user->id}", $hash);
			Messages::add('success', trans('orchestra/foundation::response.forgot.email-send'));
		}

		return Redirect::to(handles('orchestra/foundation::forgot'));
	}

	/**
	 * Once user actually visit the reset my password page, we now should be
	 * able to make the operation to create a temporary password on behave
	 * of the user
	 *
	 * GET (:orchestra)/forgot/reset/(:userId)/(:hash)
	 *
	 * @access public
	 * @param  int      $userId
	 * @param  string   $hash
	 * @return Response
	 */
	public function getReset($userId, $hash)
	{
		if ( ! (is_numeric($userId) and is_string($hash)) or empty($hash))
		{
			return App::abort(404);
		}

		$user     = User::find($userId);
		$userMeta = Memory::make('user');

		if (is_null($user) or $hash !== $userMeta->get("reset_password_hash.{$userId}"))
		{
			return App::abort(404);
		}

		$memory   = App::memory();
		$password = Str::random(5);
		$site     = $memory->get('site.name', 'Orchestra Platform');
		$data     = compact('password', 'user', 'site');

		$sent = Mail::send('orchestra/foundation::email.forgot.reset', $data, function ($m) 
			use ($site, $user)
		{
			$m->subject(trans('orchestra/foundation::email.forgot.reset', compact('site')));
			$m->to($user->email, $user->fullname);
		});

		if ($sent < 1)
		{
			Messages::add('error', trans('orchestra/foundation::response.forgot.email-fail'));
		}
		else
		{
			$userMeta->put("reset_password_hash.{$userId}", "");

			$user->password = $password;
			$user->save();

			Messages::add('success', trans('orchestra/foundation::response.forgot.email-send'));
		}

		return Redirect::to(handles('orchestra/foundation::login'));
	}
}
