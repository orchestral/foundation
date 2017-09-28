<?php

namespace Orchestra\Foundation\Processor;

use Illuminate\Session\Store;
use Orchestra\Contracts\Publisher\ServerException;
use Orchestra\Foundation\Publisher\PublisherManager;
use Orchestra\Contracts\Foundation\Command\AssetPublisher as Command;
use Orchestra\Contracts\Foundation\Listener\AssetPublishing as Listener;

class AssetPublisher extends Processor implements Command
{
    /**
     * The publisher manager implementation.
     *
     * @var \Orchestra\Foundation\Publisher\PublisherManager
     */
    protected $publisher;

    /**
     * The session store implementation.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Create a new instance of Asset Publisher.
     *
     * @param \Orchestra\Foundation\Publisher\PublisherManager $publisher
     * @param \Illuminate\Session\Store  $session
     */
    public function __construct(PublisherManager $publisher, Store $session)
    {
        $this->publisher = $publisher;
        $this->session = $session;
    }

    /**
     * Run publishing if possible.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\AssetPublishing  $listener
     *
     * @return mixed
     */
    public function executeAndRedirect(Listener $listener)
    {
        $this->publisher->connected() && $this->publisher->execute();

        return $listener->redirectToCurrentPublisher();
    }

    /**
     * Publish process.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\AssetPublishing  $listener
     * @param  array  $input
     *
     * @return mixed
     */
    public function publish(Listener $listener, array $input)
    {
        $queues = $this->publisher->queued();

        // Make an attempt to connect to service first before
        try {
            $this->publisher->connect($input);
        } catch (ServerException $e) {
            $this->session->forget('orchestra.ftp');

            return $listener->publishingHasFailed(['error' => $e->getMessage()]);
        }

        $this->session->put('orchestra.ftp', $input);

        if ($this->publisher->connected() && ! empty($queues)) {
            $this->publisher->execute();
        }

        return $listener->publishingHasSucceed();
    }
}
