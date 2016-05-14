<?php

namespace Orchestra\Foundation\Traits;

use Illuminate\Support\Str;

trait RedirectUsers
{
    /**
     * Get redirection path.
     *
     * @param  string  $namespace
     * @param  string  $path
     * @param  string|null  $redirect
     * @return string
     */
    protected function resolveUserRedirectionHandles($namespace, $path, $redirect = null)
    {
        return handles($this->resolveUserRedirectionPath($namespace, $path, $redirect));
    }

    /**
     * Get redirection path.
     *
     * @param  string  $namespace
     * @param  string  $path
     * @param  string|null  $redirect
     * @return string
     */
    protected function resolveUserRedirectionPath($namespace, $path, $redirect = null)
    {
        if (! empty($redirect)) {
            $path = $redirect;
        }

        $property = sprintf('redirect%sPath', Str::ucfirst($namespace));

        return property_exists($this, $property) ? $this->{$property} : $path;
    }
}
