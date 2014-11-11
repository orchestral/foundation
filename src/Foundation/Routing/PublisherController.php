<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Orchestra\Foundation\Processor\Publisher as PublisherProcessor;
use Orchestra\Support\Facades\Site;

class PublisherController extends AdminController
{
    /**
     * Publisher controller.
     *
     * @param  \Orchestra\Foundation\Processor\Publisher   $processor
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
        $this->beforeFilter('orchestra.csrf', array('on' => 'post'));
    }

    /**
     * Load publisher based on service.
     *
     * @return Response
     */
    public function index()
    {
        return $this->processor->index($this);
    }

    /**
     * Show FTP configuration form or run the queue.
     *
     * @return Response
     */
    public function ftp()
    {
        Site::set('title', trans('orchestra/foundation::title.publisher.ftp'));
        Site::set('description', trans('orchestra/foundation::title.publisher.description'));

        return View::make('orchestra/foundation::publisher.ftp');
    }

    /**
     * POST FTP configuration and run the queue.
     *
     * POST (orchestra)/publisher/ftp
     *
     * @return Response
     */
    public function publish()
    {
        $input = Input::only(array('host', 'user', 'password'));
        $input['ssl'] = (Input::get('connection-type', 'sftp') === 'sftp');

        return $this->processor->publish($this, $input);
    }

    /**
     * Response when publishing failed.
     *
     * @param  string|null $message
     * @return Response
     */
    public function publishFailed($message = null)
    {
        return $this->redirectWithMessage(handles('orchestra::publisher/ftp'), $message, 'error')->withInput();
    }

    /**
     * Redirect back to publisher.
     *
     * @return Response
     */
    public function redirectToPublisher()
    {
        return Redirect::to(handles('orchestra::publisher/ftp'));
    }
}
