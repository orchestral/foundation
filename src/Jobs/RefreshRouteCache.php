<?php namespace Orchestra\Foundation\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Foundation\Application;

class RefreshRouteCache extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Execute the job.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Console\Kernel  $kernel
     *
     * @return void
     */
    public function handle(Application $app, Kernel $kernel)
    {
        if (! $app->routesAreCached()) {
            return;
        }

        $kernel->call('route:cache');
    }
}
