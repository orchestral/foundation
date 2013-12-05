<?php namespace Orchestra\Foundation\Processor;

use Illuminate\Support\Facades\Session;
use Orchestra\Foundation\Routing\BaseController;
use Orchestra\Support\Facades\Publisher as PublisherManager;
use Orchestra\Support\FTP\ServerException;

class Publisher extends AbstractableProcessor
{
    /**
     * Run publishing if possible.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function index(BaseController $listener)
    {
        PublisherManager::connected() and PublisherManager::execute();

        return $listener->redirectToPublisher();
    }

    /**
     * Publish process.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  array                                           $input
     * @return mixed
     */
    public function publish(BaseController $listener, array $input)
    {
        $queues = PublisherManager::queued();

        // Make an attempt to connect to service first before
        try {
            PublisherManager::connect($input);
        } catch (ServerException $e) {
            Session::forget('orchestra.ftp');

            return $listener->publishFailed($e->getMessage());
        }

        Session::put('orchestra.ftp', $input);

        if (PublisherManager::connected() and ! empty($queues)) {
            PublisherManager::execute();
        }

        return $listener->redirectToPublisher();
    }
}
