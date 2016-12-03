<?php

namespace Orchestra\Foundation;

use Orchestra\Extension\RouteGenerator;
use Orchestra\Http\RouteResolver as Resolver;

class RouteResolver extends Resolver
{
    /**
     * Get extension route.
     *
     * @param  string   $name
     * @param  string   $default
     *
     * @return \Orchestra\Contracts\Extension\RouteGenerator
     */
    public function route($name, $default = '/')
    {
        if (in_array($name, ['orchestra', 'orchestra/foundation'])) {
            $name = 'orchestra';
        }

        return parent::route($name, $default);
    }

    /**
     * {@inheritdoc}
     */
    protected function generateRouteByName($name, $default)
    {
        // Orchestra Platform routing is managed by `orchestra/foundation::handles`
        // and can be manage using configuration.
        if (! in_array($name, ['orchestra'])) {
            return parent::generateRouteByName($name, $default);
        }

        return $this->app->make(RouteGenerator::class, [
            $this->app->make('config')->get('orchestra/foundation::handles', $default),
            $this->app->make('request'),
        ]);
    }
}
