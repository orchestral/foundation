<?php namespace Orchestra\Foundation\Publisher;

use Exception;
use Illuminate\Support\Manager;
use Orchestra\Support\Ftp as FtpClient;

class PublisherManager extends Manager
{
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
    protected function getDefaultDriver()
    {
        $memory = $this->app['orchestra.memory']->make();

        return $memory->get('orchestra.publisher.driver', 'ftp');
    }

    /**
     * Execute the queue.
     *
     * @return boolean
     */
    public function execute()
    {
        $memory   = $this->app['orchestra.memory'];
        $messages = $this->app['orchestra.messages'];
        $queues   = $this->queued();
        $fails    = array();

        foreach ($queues as $queue) {
            try {
                $this->driver()->upload($queue);

                $messages->add('success', trans('orchestra/foundation::response.extensions.activate', array(
                    'name' => $queue,
                )));
            } catch (Exception $e) {
                // this could be anything.
                $messages->add('error', $e->getMessage());
                $fails[] = $queue;
            }
        }

        $memory->put('orchestra.publisher.queue', $fails);

        return true;
    }

    /**
     * Add a process to be queue.
     *
     * @param  string   $queue
     * @return boolean
     */
    public function queue($queue)
    {
        $memory = $this->app['orchestra.memory']->make();
        $queue  = array_unique(array_merge($this->queued(), (array) $queue));
        $memory->put('orchestra.publisher.queue', $queue);

        return true;
    }

    /**
     * Get a current queue.
     *
     * @return array
     */
    public function queued()
    {
        $memory = $this->app['orchestra.memory']->make();
        return $memory->get('orchestra.publisher.queue', array());
    }
}
