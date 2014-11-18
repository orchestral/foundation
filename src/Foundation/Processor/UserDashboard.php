<?php namespace Orchestra\Foundation\Processor;

use Orchestra\Widget\WidgetManager;
use Orchestra\Foundation\Contracts\Listener\UserDashboard as Listener;

class UserDashboard extends Processor implements \Orchestra\Foundation\Contracts\Command\UserDashboard
{
    /**
     * The widget manager implementation.
     *
     * @var \Orchestra\Widget\WidgetManager
     */
    protected $widget;

    /**
     * Construct a new User Dashboard processor.
     *
     * @param \Orchestra\Widget\WidgetManager $widget
     */
    public function __construct(WidgetManager $widget)
    {
        $this->widget = $widget;
    }

    /**
     * View dashboard.
     *
     * @param  \Orchestra\Foundation\Contracts\Listener\UserDashboard  $listener
     * @return mixed
     */
    public function show(Listener $listener)
    {
        $panes = $this->widget->make('pane.orchestra');

        return $listener->showDashboard(['panes' => $panes]);
    }
}
