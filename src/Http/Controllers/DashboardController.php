<?php

namespace Orchestra\Foundation\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Orchestra\Foundation\Processors\Account\ProfileDashboard as Processor;
use Orchestra\Contracts\Foundation\Listener\Account\ProfileDashboard as Listener;

class DashboardController extends AdminController implements Listener
{
    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function onCreate()
    {
        $this->middleware('orchestra.auth', ['only' => ['show']]);
    }

    /**
     * Show User Dashboard.
     *
     * GET (:orchestra)/
     *
     * @param \Orchestra\Foundation\Processors\Account\ProfileDashboard  $processor
     *
     * @return mixed
     */
    public function show(Processor $processor)
    {
        return $processor($this);
    }

    /**
     * Show missing pages.
     *
     * GET (:orchestra) return 404
     *
     * @return mixed
     */
    public function missing()
    {
        throw new NotFoundHttpException('Controller method not found.');
    }

    /**
     * Response to show dashboard.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function showDashboard(array $data)
    {
        \set_meta('title', \trans('orchestra/foundation::title.home'));

        return \view('orchestra/foundation::dashboard.index', $data);
    }
}
