<?php namespace Orchestra\Foundation;

use Illuminate\Translation\Translator;
use Orchestra\Contracts\Foundation\Foundation as FoundationContract;

class AdminMenuHandler
{
    /**
     * Kernel instance.
     *
     * @var \Orchestra\Contracts\Foundation\Foundation
     */
    protected $foundation;

    /**
     * ACL instance.
     *
     * @var \Orchestra\Contracts\Authorization\Authorization
     */
    protected $acl;

    /**
     * Menu instance.
     *
     * @var \Orchestra\Widget\Handlers\Menu
     */
    protected $menu;

    /**
     * Translator instance.
     *
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * Construct a new handler.
     *
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     * @param  \Illuminate\Translation\Translator  $translator
     */
    public function __construct(FoundationContract $foundation, Translator $translator)
    {
        $this->foundation = $foundation;
        $this->menu = $foundation->menu();
        $this->acl = $foundation->acl();
        $this->translator = $translator;
    }

    /**
     * Create a handler for `orchestra.ready: admin` event.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
