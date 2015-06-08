<?php namespace Orchestra\Foundation\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Orchestra\Foundation\Processor\AssetPublisher;
use Orchestra\Contracts\Foundation\Listener\AssetPublishing as Listener;

class PublisherController extends AdminController implements Listener
{
    /**
     * Publisher controller.
     *
     * @param  \Orchestra\Foundation\Processor\AssetPublisher  $processor
     */
    public function __construct(AssetPublisher $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }
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
     * @return mixed
     */
    public function index()
    {
        return $this->processor->executeAndRedirect($this);
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
     * @return mixed
     */
    public function publish()
    {
        $input        = Input::only(['host', 'user', 'password']);
        $input['ssl'] = (Input::get('connection-type', 'sftp') === 'sftp');

        return $this->processor->publish($this, $input);
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
