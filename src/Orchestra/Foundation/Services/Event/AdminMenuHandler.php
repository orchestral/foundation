<?php namespace Orchestra\Foundation\Services\Event;

use Illuminate\Support\Facades\Auth;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Resources;

class AdminMenuHandler {
	
	/**
	 * Create a handler for `orchestra.ready: admin` event.
	 *
	 * @access public
	 * @return void
	 */
	public function handle()
	{
		$acl        = App::acl();
		$menu       = App::menu();
		$translator = App::make('translator');

		// Add menu when logged-user user has authorization to
		// `manage users`
		if ($acl->can('manage-users'))
		{
			$menu->add('users')
				->title($translator->trans('orchestra/foundation::title.users.list'))
				->link(App::handles('orchestra/foundation::users'));
		}

		// Add menu when logged-in user has authorization to
		// `manage orchestra`
		if ($acl->can('manage-orchestra'))
		{
			$menu->add('extensions', '>:home')
				->title($translator->trans('orchestra/foundation::title.extensions.list'))
				->link(App::handles('orchestra/foundation::extensions'));

			$menu->add('settings')
				->title($translator->trans('orchestra/foundation::title.settings.list'))
				->link(App::handles('orchestra/foundation::settings'));
		}

		$resources = Resources::all();

		// Resources menu should only be appended if there is actually
		// resources to be displayed.
		if ( ! empty($resources))
		{
			$menu->add('resources', '>:extensions')
				->title($translator->trans('orchestra/foundation::title.resources.list'))
				->link(App::handles('orchestra/foundation::resources'));

			foreach ($resources as $name => $option)
			{
				if (false === value($option->visible)) continue;

				$menu->add($name, '^:resources')
					->title($option->name)
					->link(App::handles("orchestra/foundation::resources/{$name}"));
			}
		}
	}
}
