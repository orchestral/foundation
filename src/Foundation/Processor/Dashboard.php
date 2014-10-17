<?php namespace Orchestra\Foundation\Processor;

use Orchestra\Support\Facades\Widget;

class Dashboard extends Processor
{
    /**
     * View dashboard.
     *
     * @param  object  $listener
     * @return mixed
     */
    public function show($listener)
    {
        $panes = Widget::make('pane.orchestra');

        return $listener->dashboardSucceed(array('panes' => $panes));
    }
}
