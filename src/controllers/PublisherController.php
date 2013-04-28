<?php namespace Orchestra\Foundation;

use Input;
use Redirect;
use Session;
use View;
use Orchestra\App;
use Orchestra\Messages;
use Orchestra\Publisher;
use Orchestra\Site;
use Orchestra\Support\FTP\ServerException;

class PublisherController extends AdminController {

	/**
	 * Define the filters.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->beforeFilter('orchestra.auth');
	}

	/**
	 * Load publisher based on service.
	 *
	 * @access public
	 * @return Response
	 */
	public function getIndex()
	{
		if (Publisher::connected()) Publisher::execute();

		return Redirect::to(handles('orchestra/foundation::publisher/ftp'));
	}
	

	/**
	 * Show FTP configuration form or run the queue.
	 *
	 * @access public
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
	 * @access public
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

			return Redirect::to(handles('orchestra/foundation::publisher/ftp'))->withInput();
		}

		Session::put('orchestra.ftp', $input);

		if (Publisher::connected() and ! empty($queues)) Publisher::execute();

		return Redirect::to(handles('orchestra/foundation::publisher/ftp'));
	}
}
