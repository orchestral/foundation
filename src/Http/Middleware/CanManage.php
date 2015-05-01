<?php namespace Orchestra\Foundation\Http\Middleware;

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
