<?php namespace Orchestra\Foundation;

use Event,
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
	 * Orchestra Settings Page
	 *
	 * GET (:bundle)/settings
	 *
	 * @access public
	 * @return Response
	 */
	public function get_index()
	{
		// Orchestra settings are stored using Orchestra\Memory, we need to
		// fetch it and convert it to Fluent (to mimick Eloquent properties).
		$memory   = App::memory();
		$eloquent = new Fluent(array(
			'site_name'        => $memory->get('site.name', ''),
			'site_description' => $memory->get('site.description', ''),
			'site_registrable' => ($memory->get('site.users.registration', false) ? 'yes' : 'no'),
			
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

}