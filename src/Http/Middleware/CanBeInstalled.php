<?php namespace Orchestra\Foundation\Http\Middleware;

use Orchestra\Contracts\Foundation\Foundation;

class CanBeInstalled extends Can
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
        return $this->foundation->installed();
    }

    /**
     * Response on authorized request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return mixed
     */
    protected function responseOnUnauthorized($request)
    {
        if ($request->ajax()) {
            return $this->response->make('Unauthorized', 401);
        }

        return $this->response->redirectTo($this->foundation->handles('orchestra::install'));
    }
}
