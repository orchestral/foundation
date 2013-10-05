<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Publisher;
use Orchestra\Support\Facades\Site;
use Orchestra\Support\FTP\ServerException;

class PublisherController extends AdminController {

	/**
	 * Setup controller filters.
	 *
	 * @return void
	 */
	protected function setupFilters()
	{	
		$this->beforeFilter('orchestra.auth');
	}

	/**
	 * Load publisher based on service.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		if (Publisher::connected()) Publisher::execute();

		return Redirect::to(handles('orchestra::publisher/ftp'));
	}
	

	/**
	 * Show FTP configuration form or run the queue.
	 *
	 * @return Response
	 */
	public function getFtp()
	{
		Site::set('title', trans('orchestra/foundation::title.publisher.ftp'));
		Site::set('description', trans('orchestra/foundation::title.publisher.description'));

		return View::make('orchestra/foundation::publisher.ftp');
	}

	/**
	 * POST FTP configuration and run the queue.
	 *
	 * POST (orchestra)/publisher/ftp
	 *
	 * @return Response
	 */
	public function postFtp()
	{
		$input  = Input::only(array('host', 'user', 'password'));
		$queues = Publisher::queued();

		$input['ssl'] = (Input::get('connection-type', 'sftp') === 'sftp');

		// Make an attempt to connect to service first before
		try
		{
			Publisher::connect($input);
		}
		catch(ServerException $e)
		{
			Session::forget('orchestra.ftp');
			Messages::add('error', $e->getMessage());

			return Redirect::to(handles('orchestra::publisher/ftp'))->withInput();
		}

		Session::put('orchestra.ftp', $input);

		if (Publisher::connected() and ! empty($queues)) Publisher::execute();

		return Redirect::to(handles('orchestra::publisher/ftp'));
	}
}
