<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Site;
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
     * @return Response
     */
    public function show()
    {
        Site::set('title', trans('orchestra/foundation::title.settings.list'));

        return $this->processor->show($this);
    }

    /**
     * Update Settings.
     *
     * POST (:orchestra)/settings
     *
     * @return Response
     */
    public function update()
    {
        return $this->processor->update($this, Input::all());
    }

    /**
     * Update orchestra/foundation.
     *
     * @return Response
     */
    public function migrate()
    {
        return $this->processor->migrate($this);
    }

    /**
     * Response when show setting page.
     *
     * @param  array  $data
     * @return Response
     */
    public function showSucceed(array $data)
    {
        return View::make('orchestra/foundation::settings.index', $data);
    }

    /**
     * Response when update setting failed on validation.
     *
     * @param  mixed   $validation
     * @return Response
     */
    public function updateValidationFailed($validation)
    {
        return $this->redirectWithErrors(handles('orchestra::settings') ,$validation);
    }

    /**
     * Response when update setting succeed.
     *
     * @param  string|null $message
     * @return Response
     */
    public function updateSucceed($message = null)
    {
        return $this->redirectWithMessage(handles('orchestra::settings'), $message);
    }

    /**
     * Response when update Orchestra Platform components succeed.
     *
     * @param  string|null $message
     * @return Response
     */
    public function migrateSucceed($message = null)
    {
        return $this->redirectWithMessage(handles('orchestra::settings'), $message);
    }
}
