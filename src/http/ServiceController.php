<?php

namespace rethink\hrouter\http;

use blink\http\Request;
use rethink\hrouter\CfgApi;
use rethink\hrouter\models\Service;

/**
 * ServiceController class
 */
class ServiceController extends BaseController
{
    public function index()
    {
        return services()->queryAll();
    }

    public function create(Request $request)
    {
        $body = $request->getBody();

        $service = services()->create($body->all());

        haproxy()->reloadAsync(true);

        return $this->ok($service);
    }

    public function update($name, Request $request)
    {
        $service = services()->update($name, $request->body->all());

        haproxy()->reloadAsync(true);

        return $this->ok($service, 200);
    }

    public function view($name)
    {
        return services()->loadOrFail($name);
    }

    public function delete($name)
    {
        services()->delete($name);

        haproxy()->reloadAsync(true);

        return $this->noContent();
    }
}
