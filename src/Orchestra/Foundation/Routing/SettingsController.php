<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Site;
use Orchestra\Foundation\Services\Html\SettingPresenter;
use Orchestra\Foundation\Services\Validation\Setting as SettingValidator;

class SettingsController extends AdminController {

	/**
	 * Construct Settings Controller, only authenticated user should be able
	 * to access this controller.
	 *
	 * @param  \Orchestra\Foundation\Services\Html\SettingPresenter     $presenter
	 * @param  \Orchestra\Foundation\Services\Validation\Setting        $validator
	 */
	public function __construct(SettingPresenter $presenter, SettingValidator $validator)
	{
		parent::__construct();

		$this->presenter = $presenter;
		$this->validator = $validator;

		$this->beforeFilter('orchestra.auth');
		$this->beforeFilter('orchestra.manage');
	}

	/**
	 * Show Settings Page
	 *
	 * GET (:orchestra)/settings
	 *
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
			'email_sendmail'   => $memory->get('email.sendmail', ''),
			'email_queue'      => ($memory->get('email.queue', false) ? 'yes' : 'no'),
		));

		$form = $this->presenter->form($eloquent);

		Event::fire('orchestra.form: settings', array($eloquent, $form));
		Site::set('title', trans('orchestra/foundation::title.settings.list'));

		return View::make('orchestra/foundation::settings.index', array(
			'eloquent' => $eloquent, 
			'form'     => $form,
		));
	}

	/**
	 * Update Settings
	 *
	 * POST (:orchestra)/settings
	 *
	 * @return Response
	 */
	public function postIndex()
	{
		$default = array('email_driver' => 'mail');
		$input   = array_merge($default, Input::all());

		$validation = $this->validator->on($input['email_driver'])->with($input);

		if ($validation->fails())
		{
			return Redirect::to(handles('orchestra::settings'))
					->withInput()
					->withErrors($validation);
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
		$memory->put('email.sendmail', $input['email_sendmail']);
		$memory->put('email.queue', ($input['email_queue'] === 'yes'));

		Event::fire('orchestra.saved: settings', array($memory, $input));
		Messages::add('success', trans('orchestra/foundation::response.settings.update'));

		return Redirect::to(handles('orchestra::settings'));
	}

	/**
	 * Update orchestra/foundation.
	 *
	 * @return Response
	 */
	public function getUpdate()
	{
		App::make('orchestra.publisher.asset')->foundation();
		App::make('orchestra.publisher.migrate')->foundation();

		Messages::add('success', trans('orchestra/foundation::response.settings.system-update'));
		return Redirect::to(handles('orchestra::settings'));
	}
}
