<?php

namespace Orchestra\Foundation\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Orchestra\Foundation\Processor\AssetPublisher;
use Orchestra\Contracts\Foundation\Listener\AssetPublishing as Listener;

class PublisherController extends AdminController implements Listener
{
    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function setupMiddleware()
    {
        $this->middleware('orchestra.auth');
    }

    /**
     * Load publisher based on service.
     *
     * @param  \Orchestra\Foundation\Processor\AssetPublisher  $processor
     *
     * @return mixed
     */
    public function index(AssetPublisher $processor)
    {
        return $processor->executeAndRedirect($this);
    }

    /**
     * Show FTP configuration form or run the queue.
     *
     * @return mixed
     */
    public function ftp()
    {
        set_meta('title', trans('orchestra/foundation::title.publisher.ftp'));
        set_meta('description', trans('orchestra/foundation::title.publisher.description'));

        return view('orchestra/foundation::publisher.ftp');
    }

    /**
     * POST FTP configuration and run the queue.
     *
     * POST (orchestra)/publisher/ftp
     *
     * @param  \Orchestra\Foundation\Processor\AssetPublisher  $processor
     *
     * @return mixed
     */
    public function publish(AssetPublisher $processor)
    {
        $input        = Input::only(['host', 'user', 'password']);
        $input['ssl'] = (Input::get('connection-type', 'sftp') === 'sftp');

        return $processor->publish($this, $input);
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
        return $this->redirectWithMessage(handles('orchestra::publisher/ftp'), $errors['error'], 'error')->withInput();
    }

    /**
     * Response to publishing asset succeed.
     *
     * @return mixed
     */
    public function publishingHasSucceed()
    {
        return $this->redirectToCurrentPublisher();
    }

    /**
     * Redirect back to current publisher.
     *
     * @return mixed
     */
    public function redirectToCurrentPublisher()
    {
        return $this->redirect(handles('orchestra::publisher/ftp'));
    }
}
