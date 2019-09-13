<?php

namespace Orchestra\Foundation\Processors\Account;

use Orchestra\Contracts\Foundation\Command\Account\ProfileDashboard as Command;
use Orchestra\Contracts\Foundation\Listener\Account\ProfileDashboard as Listener;
use Orchestra\Foundation\Processors\Processor;
use Orchestra\Widget\WidgetManager;

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

    /**
     * Invoke the processor.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\ProfileDashboard  $listener
     *
     * @return mixed
     */
    public function __invoke(Listener $listener)
    {
        return $this->show($listener);
    }
}
