<?php

namespace Orchestra\Foundation\Bootstrap;

use Orchestra\Support\Facades\Messages;
use Orchestra\Contracts\Messages\MessageBag;
use Illuminate\Contracts\Foundation\Application;

class NotifyIfSafeMode
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
        if ($app->make('orchestra.extension.status')->is('safe')) {
            Messages::extend(static function (MessageBag $messages) {
                $messages->add('info', \trans('orchestra/foundation::response.safe-mode'));
            });
        }
    }
}
