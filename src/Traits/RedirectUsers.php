<?php

namespace Orchestra\Foundation\Traits;

trait RedirectUsers
{
    /**
     * Get redirection path.
     *
     * @param  string  $path
     * @param  string|null  $redirect
     * @return string
     */
    protected function resolveUserRedirectionPath($path, $redirect = null)
    {
        if (! empty($redirect)) {
            $path = $redirect;
        }

        return handles($path);
    }
}
