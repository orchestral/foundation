<?php

namespace Orchestra\Foundation\Publisher;

use Exception;
use RuntimeException;
use Illuminate\Support\Manager;
use Orchestra\Memory\Memorizable;

class PublisherManager extends Manager
{
    use Memorizable;

    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    protected function createDriver($driver)
    {
        $name = "orchestra.publisher.{$driver}";

        if (! $this->container->bound($name)) {
            throw new RuntimeException("Unable to resolve [{$driver}] publisher");
        }

        return $this->container->make($name);
    }

    /**
     * Get the default authentication driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->memory->get('orchestra.publisher.driver', 'filesystem');
    }

    /**
     * Set the default authentication driver name.
     *
     * @return $this
     */
    public function setDefaultDriver(string $driver)
    {
        $this->memory->put('orchestra.publisher.driver', 'filesystem');
    }

    /**
     * Verify that the driver is connected to a service.
     *
     * @return bool
     */
    public function connected(): bool
    {
        return $this->driver()->connected();
    }

    /**
     * Execute the queue.
     *
     * @return bool
     */
    public function execute(): bool
    {
        $messages = $this->app->make('orchestra.messages');
        $queues = $this->queued();
        $fails = [];

        foreach ($queues as $queue) {
            try {
                $this->driver()->upload($queue);

                $messages->add('success', trans('orchestra/foundation::response.extensions.activate', [
                    'name' => $queue,
                ]));
            } catch (Exception $e) {
                // this could be anything.
                $messages->add('error', $e->getMessage());
                $fails[] = $queue;
            }
        }

        $this->memory->put('orchestra.publisher.queue', $fails);

        return true;
    }

    /**
     * Add a process to be queue.
     *
     * @param  string|array  $queue
     *
     * @return bool
     */
    public function queue($queue): bool
    {
        $queue = \array_unique(\array_merge($this->queued(), (array) $queue));
        $this->memory->put('orchestra.publisher.queue', $queue);

        return true;
    }

    /**
     * Get a current queue.
     *
     * @return array
     */
    public function queued(): array
    {
        return $this->memory->get('orchestra.publisher.queue', []);
    }
}
