<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
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
     * Show Settings Page
     *
     * GET (:orchestra)/settings
     *
     * @return Response
     */
    public function getIndex()
    {
        Site::set('title', trans('orchestra/foundation::title.settings.list'));

        return $this->processor->show($this);
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
        return $this->processor->update($this, Input::all());
    }

    /**
     * Update orchestra/foundation.
     *
     * @return Response
     */
    public function getMigrate()
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
        return Redirect::to(handles('orchestra::settings'))
                    ->withInput()
                    ->withErrors($validation);
    }

    /**
     * Response when update setting succeed.
     *
     * @param  string  $message
     * @return Response
     */
    public function updateSucceed($message)
    {
        Messages::add('success', $message);

        return Redirect::to(handles('orchestra::settings'));
    }

    /**
     * Response when update Orchestra Platform components succeed.
     *
     * @param  string  $message
     * @return Response
     */
    public function migrateSucceed($message)
    {
        Messages::add('success', $message);

        return Redirect::to(handles('orchestra::settings'));
    }
}
