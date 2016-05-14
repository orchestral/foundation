<?php

namespace Orchestra\Foundation\Traits;

use Illuminate\Support\Str;

trait RedirectUsers
{
    /**
     * Get redirection handles.
     *
     * @param  string  $namespace
     * @param  string  $path
     * @param  string|null  $redirect
     * @return string
     */
    protected function redirectUserTo($namespace, $path, $redirect = null)
    {
        return handles($this->redirectUserPath($namespace, $path, $redirect));
    }

    /**
     * Get redirection path.
     *
     * @param  string  $namespace
     * @param  string  $path
     * @param  string|null  $redirect
     * @return string
     */
    protected function redirectUserPath($namespace, $path, $redirect = null)
    {
        if (! empty($redirect)) {
            $path = $redirect;
        }

        $property = sprintf('redirect%sPath', Str::ucfirst($namespace));

        return property_exists($this, $property) ? $this->{$property} : $path;
    }
}
