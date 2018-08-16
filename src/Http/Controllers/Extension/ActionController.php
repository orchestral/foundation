<?php

namespace Orchestra\Foundation\Http\Controllers\Extension;

use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Publisher;

abstract class ActionController extends Controller
{
    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function onCreate()
    {
        $this->middleware([
            'orchestra.auth',
            'orchestra.can:manage-orchestra',
            'orchestra.csrf',
        ]);
    }

    /**
     * Queue publishing asset to publisher.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     *
     * @return mixed
     */
    protected function queueToPublisher(Fluent $extension)
    {
        Publisher::queue($extension->get('name'));

        return $this->redirect(handles('orchestra::publisher'));
    }
}
