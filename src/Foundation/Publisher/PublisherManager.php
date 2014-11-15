<?php namespace Orchestra\Foundation\Publisher;

use Exception;
use Illuminate\Support\Manager;

class PublisherManager extends Manager
{
    /**
     * Memory Provider instance.
     *
     * @var \Orchestra\Memory\Provider
     */
    protected $memory;

    /**
     * {@inheritdoc}
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->memory = $app['orchestra.platform.memory'];
    }

    /**
     * Create an instance of the Ftp driver.
     *
     * @return \Orchestra\Foundation\Publisher\Ftp
     */
    protected function createFtpDriver()
    {
        return new Ftp($this->app, $this->app['orchestra.publisher.ftp']);
    }

    /**
     * Get the default authentication driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->memory->get('orchestra.publisher.driver', 'ftp');
    }

    /**
     * Execute the queue.
     *
     * @return bool
     */
    public function execute()
    {
        $messages = $this->app['orchestra.messages'];
        $queues   = $this->queued();
        $fails    = [];

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
     * @param  string  $queue
     * @return bool
     */
    public function queue($queue)
    {
        $queue = array_unique(array_merge($this->queued(), (array) $queue));
        $this->memory->put('orchestra.publisher.queue', $queue);

        return true;
    }

    /**
     * Get a current queue.
     *
     * @return array
     */
    public function queued()
    {
        return $this->memory->get('orchestra.publisher.queue', []);
    }
}
