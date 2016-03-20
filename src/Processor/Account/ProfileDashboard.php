<?php

namespace Orchestra\Foundation\Processor\Account;

use Orchestra\Widget\WidgetManager;
use Orchestra\Foundation\Processor\Processor;
use Orchestra\Contracts\Foundation\Command\Account\ProfileDashboard as Command;
use Orchestra\Contracts\Foundation\Listener\Account\ProfileDashboard as Listener;

class ProfileDashboard extends Processor implements Command
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
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\ProfileDashboard  $listener
     *
     * @return mixed
     */
    public function show(Listener $listener)
    {
        $panes = $this->widget->make('pane.orchestra');

        return $listener->showDashboard(['panes' => $panes]);
    }
}
