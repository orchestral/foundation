<?php namespace Orchestra\Foundation\Routing;

use Orchestra\Support\Facades\Meta;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Orchestra\Foundation\Processor\Setting as SettingProcessor;

class SettingsController extends AdminController
{
    /**
     * Settings configuration Controller for the application.
     *
     * @param  \Orchestra\Foundation\Processor\Setting  $processor
     */
    public function __construct(SettingProcessor $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }

    /**
     * Setup controller filters.
     *
     * @return void
     */
    protected function setupFilters()
    {
        $this->beforeFilter('orchestra.auth');
        $this->beforeFilter('orchestra.manage');
    }

    /**
     * Show Settings Page.
     *
     * GET (:orchestra)/settings
     *
     * @return mixed
     */
    public function show()
    {
        return $this->processor->show($this);
    }

    /**
     * Update Settings.
     *
     * POST (:orchestra)/settings
     *
     * @return mixed
     */
    public function update()
    {
        return $this->processor->update($this, Input::all());
    }

    /**
     * Update orchestra/foundation.
     *
     * @return mixed
     */
    public function migrate()
    {
        return $this->processor->migrate($this);
    }

    /**
     * Response when show setting page.
     *
     * @param  array  $data
     * @return mixed
     */
    public function showSucceed(array $data)
    {
        Meta::set('title', trans('orchestra/foundation::title.settings.list'));

        return View::make('orchestra/foundation::settings.index', $data);
    }

    /**
     * Response when update setting failed on validation.
     *
     * @param  mixed   $validation
     * @return mixed
     */
    public function updateValidationFailed($validation)
    {
        return $this->redirectWithErrors(handles('orchestra::settings'), $validation);
    }

    /**
     * Response when update setting succeed.
     *
     * @return mixed
     */
    public function updateSucceed()
    {
        $message = trans('orchestra/foundation::response.settings.update');

        return $this->redirectWithMessage(handles('orchestra::settings'), $message);
    }

    /**
     * Response when update Orchestra Platform components succeed.
     *
     * @return mixed
     */
    public function migrateSucceed()
    {
        $message = trans('orchestra/foundation::response.settings.system-update');

        return $this->redirectWithMessage(handles('orchestra::settings'), $message);
    }
}
