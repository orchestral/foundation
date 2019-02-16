<?php

namespace Orchestra\Foundation\Concerns;

use Illuminate\Support\Str;

trait RedirectUsers
{
    /**
     * Get redirection handles.
     *
     * @param  string  $namespace
     * @param  string  $path
     * @param  string|null  $redirect
     *
     * @return string
     */
    protected function redirectUserTo(string $namespace, string $path, ?string $redirect = null): string
    {
        return \handles($this->redirectUserPath($namespace, $path, $redirect));
    }

    /**
     * Get redirection path.
     *
     * @param  string  $namespace
     * @param  string  $path
     * @param  string|null  $redirect
     *
     * @return string
     */
    protected function redirectUserPath(string $namespace, string $path, ?string $redirect = null): string
    {
        if (! empty($redirect)) {
            $path = $redirect;
        }

        $property = \sprintf('redirect%sPath', Str::ucfirst($namespace));

        return \property_exists($this, $property) ? $this->{$property} : $path;
    }
}
