<?php namespace Orchestra\Foundation\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Orchestra\Foundation\Processor\Setting as Processor;
use Orchestra\Contracts\Foundation\Listener\SystemUpdater;
use Orchestra\Contracts\Foundation\Listener\SettingUpdater;

class SettingsController extends AdminController implements SystemUpdater, SettingUpdater
{
    /**
     * Settings configuration Controller for the application.
     *
     * @param  \Orchestra\Foundation\Processor\Setting  $processor
     */
    public function __construct(Processor $processor)
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
        $this->beforeFilter('orchestra.csrf', ['only' => 'migrate']);
    }

    /**
     * Show Settings Page.
     *
     * GET (:orchestra)/settings
     *
     * @return mixed
     */
    public function edit()
    {
        return $this->processor->edit($this);
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
     *
     * @return mixed
     */
    public function showSettingChanger(array $data)
    {
        set_meta('title', trans('orchestra/foundation::title.settings.list'));

        return view('orchestra/foundation::settings.index', $data);
    }

    /**
     * Response when update setting failed on validation.
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     *
     * @return mixed
     */
    public function settingFailedValidation($errors)
    {
        return $this->redirectWithErrors(handles('orchestra::settings'), $errors);
    }

    /**
     * Response when update setting succeed.
     *
     * @return mixed
     */
    public function settingHasUpdated()
    {
        $message = trans('orchestra/foundation::response.settings.update');

        return $this->redirectWithMessage(handles('orchestra::settings'), $message);
    }

    /**
     * Response when update Orchestra Platform components succeed.
     *
     * @return mixed
     */
    public function systemHasUpdated()
    {
        $message = trans('orchestra/foundation::response.settings.system-update');

        return $this->redirectWithMessage(handles('orchestra::settings'), $message);
    }
}
