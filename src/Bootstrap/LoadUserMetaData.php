<?php

namespace Orchestra\Foundation\Bootstrap;

use Orchestra\Model\Memory\UserMetaProvider;
use Orchestra\Model\Memory\UserMetaRepository;
use Illuminate\Contracts\Foundation\Application;

class LoadUserMetaData
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app->make('orchestra.memory')->extend('user', function ($app, $name) {
            $handler = new UserMetaRepository($name, [], $app);

            return new UserMetaProvider($handler);
        });
    }
}
