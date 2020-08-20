<?php


namespace Cblink\Service\Kennel\Providers;


use Cblink\Service\Kennel\ServiceContainer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $app['http_client'] = function (ServiceContainer $app) {
            return Request::createFromGlobals();
        };
    }
}
