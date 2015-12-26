<?php namespace Orchestra\Foundation\Http\Middleware;

/**
 * @deprecated since 3.2.0 and to be removed on 3.3.0.
 * @see \Orchestra\Http\Middleware\Can
 */
class CanManage extends Can
{
    /**
     * Check authorization.
     *
     * @param  string  $action
     *
     * @return bool
     */
    protected function authorize($action = null)
    {
        $action = $action ?: 'orchestra';

        return $this->foundation->acl()->can("manage-{$action}");
    }
}
