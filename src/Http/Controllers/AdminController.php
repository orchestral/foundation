<?php

namespace Orchestra\Foundation\Http\Controllers;

abstract class AdminController extends BaseController
{
    /**
     * Base construct method.
     */
    public function __construct()
    {
        $this->middleware('orchestra.installable');

        parent::__construct();
    }
}
