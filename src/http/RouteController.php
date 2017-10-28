<?php

namespace rethink\hrouter\http;

use rethink\hrouter\CfgApi;
use blink\http\Request;

/**
 * Class RouteController
 *
 * @package rethink\hrouter\restapi
 */
class RouteController extends BaseController
{
    public function index($serviceId)
    {
        $service = services()->loadOrFail($serviceId);

        return $service->routes;
    }

    public function create($serviceId, Request $request)
    {
        $service = services()->loadOrFail($serviceId);

        $attributes = $request->body->all();
        $attributes['service_id'] = $service->id;

        $route = routes()->create($attributes);

        haproxy()->reloadAsync(true);

        return $this->ok($route);
    }

    public function view($serviceId, $routeId)
    {
        return routes()->loadInServiceOrFail($serviceId, $routeId);
    }

    public function update($serviceId, $routeId, Request $request)
    {
        $route = routes()->loadInServiceOrFail($serviceId, $routeId);

        $route = routes()->update($route, $request->body->all());

        haproxy()->reloadAsync(true);

        return $this->ok($route, 200);
    }

    public function delete($serviceId, $routeId)
    {
        $route = routes()->loadInServiceOrFail($serviceId, $routeId);

        routes()->delete($route);

        haproxy()->reloadAsync(true);

        return $this->noContent();
    }
}