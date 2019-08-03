<?php

namespace Orchestra\Foundation\Http\Controllers;

use Orchestra\Foundation\Processors\AssetPublisher;
use Orchestra\Contracts\Foundation\Listener\AssetPublishing as Listener;

class PublisherController extends AdminController implements Listener
{
    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function onCreate()
    {
        $this->middleware([
            'orchestra.auth',
            'orchestra.can:manage-orchestra',
        ]);
    }

    /**
     * Load publisher based on service.
     *
     * @param  \Orchestra\Foundation\Processors\AssetPublisher  $processor
     *
     * @return mixed
     */
    public function show(AssetPublisher $processor)
    {
        return $processor->executeAndRedirect($this);
    }

    /**
     * Response to publishing asset failed.
     *
     * @param  array  $errors
     *
     * @return mixed
     */
    public function publishingHasFailed(array $errors)
    {
        return $this->redirectWithMessage(\handles('orchestra::extensions'), $errors['error'], 'error')->withInput();
    }

    /**
     * Response to publishing asset succeed.
     *
     * @return mixed
     */
    public function publishingHasSucceed()
    {
        return $this->redirect(\handles('orchestra::extensions'));
    }
}
