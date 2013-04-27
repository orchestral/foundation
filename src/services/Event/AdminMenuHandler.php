<?php namespace Orchestra\Services\Event;

use Orchestra\App;

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
				->link(handles('orchestra/foundation::users'));

			$menu->add('add-users', '^:users')
				->title($translator->trans('orchestra/foundation::title.users.create'))
				->link(handles('orchestra/foundation::users/create'));
		}

		// Add menu when logged-in user has authorization to
		// `manage orchestra`
		if ($acl->can('manage-orchestra'))
		{
			$menu->add('extensions', '>:home')
				->title($translator->trans('orchestra/foundation::title.extensions.list'))
				->link(handles('orchestra/foundation::extensions'));

			$menu->add('settings')
				->title($translator->trans('orchestra/foundation::title.settings.list'))
				->link(handles('orchestra/foundation::settings'));
		}
	}
}
