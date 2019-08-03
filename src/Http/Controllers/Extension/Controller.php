<?php

namespace Orchestra\Foundation\Http\Controllers\Extension;

use Illuminate\Support\Fluent;
use Orchestra\Contracts\Extension\Listener\Extension;
use Orchestra\Foundation\Http\Controllers\AdminController;

abstract class Controller extends AdminController implements Extension
{
    /**
     * Abort request when extension requirement mismatched.
     *
     * @return mixed
     */
    public function abortWhenRequirementMismatched()
    {
        return $this->suspend(404);
    }

    /**
     * Get extension information.
     *
     * @param  string  $vendor
     * @param  string|null  $package
     *
     * @return \Illuminate\Support\Fluent
     */
    protected function getExtension($vendor, $package = null)
    {
        $name = (\is_null($package) ? $vendor : \implode('/', [$vendor, $package]));

        return new Fluent(['name' => $name, 'uid' => $name]);
    }
}
