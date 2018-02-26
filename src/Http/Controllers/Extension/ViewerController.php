<?php

namespace Orchestra\Foundation\Http\Controllers\Extension;

use Orchestra\Contracts\Extension\Listener\Viewer as Listener;
use Orchestra\Foundation\Processor\Extension\Viewer as Processor;

class ViewerController extends Controller implements Listener
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
        ]);
    }

    /**
     * List all available extensions.
     *
     * GET (:orchestra)/extensions
     *
     * @param \Orchestra\Foundation\Processor\Extension\Viewer  $processor
     *
     * @return mixed
     */
    public function index(Processor $processor)
    {
        return $processor->index($this);
    }

    /**
     * Response for list of extensions viewer.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function showExtensions(array $data)
    {
        set_meta('title', trans('orchestra/foundation::title.extensions.list'));

        return view('orchestra/foundation::extensions.index', $data);
    }
}
