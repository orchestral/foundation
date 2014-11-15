<?php namespace Orchestra\Foundation\Bootstrap;

use Illuminate\Contracts\Foundation\Application;

class LoadFoundation
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app['orchestra.app']->boot();
    }
}
