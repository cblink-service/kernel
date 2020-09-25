<?php


namespace Cblink\Service\Kennel\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Cblink\Service\Kennel\ServiceContainer;
use Symfony\Component\HttpFoundation\Request;

class RequestServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $app['request'] = function (ServiceContainer $app) {
            return Request::createFromGlobals();
        };
    }
}
