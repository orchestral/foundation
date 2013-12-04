<?php namespace Orchestra\Foundation\Processor;

use Orchestra\Foundation\Routing\BaseController;
use Orchestra\Support\Facades\Widget;

class Dashboard extends AbstractableProcessor
{
    /**
     * View dashboard.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function show(BaseController $listener)
    {
        $panes = Widget::make('pane.orchestra');

        return $listener->dashboardSucceed(compact('panes'));
    }
}
