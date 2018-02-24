<?php

namespace Orchestra\Foundation;

use Orchestra\Http\RouteResolver as Resolver;

class RouteResolver extends Resolver
{
    /**
     * Get extension route.
     *
     * @param  string   $name
     * @param  string   $default
     *
     * @return \Orchestra\Contracts\Extension\UrlGenerator
     */
    public function route(string $name, string $default = '/')
    {
        if (in_array($name, ['orchestra', 'orchestra/foundation'])) {
            $name = 'orchestra';
        }

        return parent::route($name, $default);
    }

    /**
     * {@inheritdoc}
     */
    protected function generateRouteByName(string $name, string $default)
    {
        // Orchestra Platform routing is managed by `orchestra/foundation::handles`
        // and can be manage using configuration.
        if (in_array($name, ['orchestra'])) {
            $url = $this->app->make('config')->get('orchestra/foundation::handles', $default);

            return $this->app->make('orchestra.extension.url')->handle(
                is_string($url) ? $url : 'admin'
            );
        }

        return parent::generateRouteByName($name, $default);
    }
}
