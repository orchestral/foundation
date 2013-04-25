<?php namespace Orchestra\Foundation;

use Event,
	Input,
	View,
	Illuminate\Support\Fluent,
	Orchestra\App,
	Orchestra\Site,
	Orchestra\Services\Html\SettingPresenter;

class SettingsController extends AdminController {

	/**
	 * Construct Settings Controller, only authenticated user should be able
	 * to access this controller.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter('orchestra.auth');
		$this->beforeFilter('orchestra.manage');
	}

	/**
	 * Show Settings Page
	 *
	 * GET (:orchestra)/settings
	 *
	 * @access public
	 * @return Response
	 */
	public function getIndex()
	{
		// Orchestra settings are stored using Orchestra\Memory, we need to
		// fetch it and convert it to Fluent (to mimick Eloquent properties).
		$memory   = App::memory();
		$eloquent = new Fluent(array(
			'site_name'        => $memory->get('site.name', ''),
			'site_description' => $memory->get('site.description', ''),
			'site_registrable' => ($memory->get('site.registrable', false) ? 'yes' : 'no'),
			
			'email_driver'     => $memory->get('email.driver', ''),
			'email_address'    => $memory->get('email.from.address', ''),
			'email_host'       => $memory->get('email.host', ''),
			'email_port'       => $memory->get('email.port', ''),
			'email_username'   => $memory->get('email.username', ''),
			'email_password'   => $memory->get('email.password', ''),
			'email_encryption' => $memory->get('email.encryption', ''),
		));

		$form = SettingPresenter::form($eloquent);

		Event::fire('orchestra.form: settings', array($eloquent, $form));
		Site::set('title', trans('orchestra/foundation::title.settings.list'));

		return View::make('orchestra/foundation::settings.index', compact('eloquent', 'form'));
	}

	/**
	 * Update Settings
	 *
	 * POST (:orchestra)/settings
	 *
	 * @access public
	 * @return Response
	 */
	public function postIndex()
	{
		$input      = Input::all();
		$validation = App::make('');
		
		if ( ! isset($input['email_driver'])
		{
			$input['email_driver'] = 'mail';
		}
		elseif ($input['email_driver'] === 'smtp')
		{
			$input['email_address'] = $input['email_username'];
		}

		$validation = Validator::make($input, $rules);

		if ($val->fails())
		{
			return Redirect::to(handles('orchestra::settings'))
					->with_input()
					->with_errors($val);
		}

		$memory = App::memory();

		$memory->put('site.name', $input['site_name']);
		$memory->put('site.description', $input['site_description']);
		$memory->put('site.registrable', ($input['site_registrable'] === 'yes'));
		$memory->put('email.driver', $input['email_driver']);

		$memory->put('email.from', array(
			'address' => $input['email_address'],
			'name'    => $input['site_name'],
		));

		if ((empty($input['email_password']) and $input['change_password'] === 'no'))
		{
			$input['email_password'] = $memory->get('email.password');	
		}
		
		$memory->put('email.host', $input['email_host']);
		$memory->put('email.port', $input['email_port']);
		$memory->put('email.username', $input['email_username']);
		$memory->put('email.password', $input['email_password']);
		$memory->put('email.encryption', $input['email_encryption']);

		Event::fire('orchestra.saved: settings', array($memory, $input));
		Messages::add('success', __('orchestra::response.settings.update'));

		return Redirect::to(handles('orchestra/foundation::settings'));
	}
}