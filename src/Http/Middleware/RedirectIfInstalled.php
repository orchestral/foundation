<?php

namespace Orchestra\Foundation\Http\Middleware;

class RedirectIfInstalled extends Can
{
    /**
     * Check authorization.
     *
     * @param  string  $action
     *
     * @return bool
     */
    protected function authorize(?string $action = null): bool
    {
        return ! $this->foundation->installed();
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

        $type = ($this->auth->guest() ? 'guest' : 'user');
        $url = $this->config->get("orchestra/foundation::routes.{$type}");

        return $this->response->redirectTo($this->foundation->handles($url));
    }
}
