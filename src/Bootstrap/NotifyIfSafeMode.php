<?php namespace Orchestra\Foundation\Bootstrap;

use Illuminate\Contracts\Foundation\Application;

class NotifyIfSafeMode
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app['events']->listen('orchestra.extension: booted', function () use ($app) {
            if ('on' === $app['session']->get('orchestra.safemode')) {
                $app['orchestra.messages']->extend(function ($messages) {
                    $messages->add('info', trans('orchestra/foundation::response.safe-mode'));
                });
            }
        });
    }
}
