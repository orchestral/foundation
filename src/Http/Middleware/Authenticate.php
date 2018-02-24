<?php

namespace Orchestra\Foundation\Http\Middleware;

class Authenticate extends Can
{
    /**
     * Check authorization.
     *
     * @param  string|null  $action
     *
     * @return bool
     */
    protected function authorize(?string $action = null): bool
    {
        return $this->auth->check();
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

        $url = $this->config->get('orchestra/foundation::routes.guest');

        return $this->response->redirectGuest($this->foundation->handles($url));
    }
}
