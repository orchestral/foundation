<?php

namespace Orchestra\Foundation\Http\Middleware;

class RedirectIfAuthenticated extends Can
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
        return $this->auth->guest();
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

        $url = $this->config->get('orchestra/foundation::routes.user');

        return $this->response->redirectTo($this->foundation->handles($url));
    }
}
