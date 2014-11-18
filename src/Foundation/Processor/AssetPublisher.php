<?php namespace Orchestra\Foundation\Processor;

use Illuminate\Support\Facades\Session;
use Orchestra\Support\Ftp\ServerException;
use Orchestra\Support\Facades\Publisher;
use Orchestra\Foundation\Contracts\Listener\AssetPublishing as Listener;

class AssetPublisher extends Processor
{
    /**
     * Run publishing if possible.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\AssetPublishing  $listener
     * @return mixed
     */
    public function index(Listener $listener)
    {
        Publisher::connected() && Publisher::execute();

        return $listener->redirectToCurrentPublisher();
    }

    /**
     * Publish process.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\AssetPublishing  $listener
     * @param  array  $input
     * @return mixed
     */
    public function publish(Listener $listener, array $input)
    {
        $queues = Publisher::queued();

        // Make an attempt to connect to service first before
        try {
            Publisher::connect($input);
        } catch (ServerException $e) {
            Session::forget('orchestra.ftp');

            return $listener->publishingAssetFailed(['error' => $e->getMessage()]);
        }

        Session::put('orchestra.ftp', $input);

        if (Publisher::connected() && ! empty($queues)) {
            Publisher::execute();
        }

        return $listener->assetPublished();
    }
}
