<?php namespace Orchestra;

use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Resources;
use Orchestra\Support\Facades\Site;
use Orchestra\Services\Html\ResourcePresenter;

class ResourcesController extends AdminController {

	/**
	 * Construct Resources Controller, only authenticated user should be able
	 * to access this controller.
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
	 * Route to Resources List.
	 *
	 * @access public
	 * @return Response
	 */
	public function anyIndex()
	{
		$resources  = Resources::all();
		$collection = array();

		foreach ($resources as $name => $options)
		{
			if (false === value($options->visible)) continue;
			
			$collection[$name] = $options;
		}

		$table = ResourcePresenter::table($collection);

		Site::set('title', trans('orchestra/foundation::title.resources.list'));
		Site::set('description', trans('orchestra/foundation::title.resources.list-detail'));

		return View::make('orchestra/foundation::resources.index', compact('table'));
	}

	/**
	 * Add a drop-in resource anywhere on Orchestra
	 *
	 * @access public
	 * @param  string $request
	 * @param  array  $arguments
	 * @return Response
	 */
	public function call($request)
	{
		$resources  = Resources::all();
		$parameters = explode('/', trim($request, '/'));
		$name       = array_shift($parameters);
		$content    = Resources::call($name, $parameters);

		return Resources::response($content, function ($content) use ($resources, $name, $request)
		{
			( ! str_contains($name, '.')) ?
				$namespace = $name : list($namespace,) = explode('.', $name, 2);

			return View::make('orchestra/foundation::resources.page', array(
				'content'   => $content,
				'resources' => array(
					'list'      => $resources,
					'namespace' => $namespace,
					'name'      => $name,
					'request'   => $request,
				),
			));
		});
	}
}
