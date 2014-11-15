<?php namespace Orchestra\Foundation\Routing;

use Orchestra\Support\Facades\Meta;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Orchestra\Foundation\Processor\Publisher as PublisherProcessor;

class PublisherController extends AdminController
{
    /**
     * Publisher controller.
     *
     * @param  \Orchestra\Foundation\Processor\Publisher  $processor
     */
    public function __construct(PublisherProcessor $processor)
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
    }

    /**
     * Load publisher based on service.
     *
     * @return mixed
     */
    public function index()
    {
        return $this->processor->index($this);
    }

    /**
     * Show FTP configuration form or run the queue.
     *
     * @return mixed
     */
    public function ftp()
    {
        Meta::set('title', trans('orchestra/foundation::title.publisher.ftp'));
        Meta::set('description', trans('orchestra/foundation::title.publisher.description'));

        return View::make('orchestra/foundation::publisher.ftp');
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
        $input = Input::only(['host', 'user', 'password']);
        $input['ssl'] = (Input::get('connection-type', 'sftp') === 'sftp');

        return $this->processor->publish($this, $input);
    }

    /**
     * Response when publishing failed.
     *
     * @param  string|null  $message
     * @return mixed
     */
    public function publishFailed($message = null)
    {
        return $this->redirectWithMessage(handles('orchestra::publisher/ftp'), $message, 'error')->withInput();
    }

    /**
     * Redirect back to publisher.
     *
     * @return mixed
     */
    public function redirectToPublisher()
    {
        return Redirect::to(handles('orchestra::publisher/ftp'));
    }
}
