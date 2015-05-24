<?php namespace Orchestra\Foundation\Http\Controllers;

abstract class AdminController extends BaseController
{
    /**
     * Base construct method.
     */
    public function __construct()
    {
        // Admin controllers should be accessible only after
        // Orchestra Platform is installed.
        $this->middleware('orchestra.installable');

        parent::__construct();
    }
}
