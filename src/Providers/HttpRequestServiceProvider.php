<?php
namespace Cblink\Service\Kennel\Providers;

use Cblink\Service\Kennel\HttpRequest;
use Cblink\Service\Kennel\Interfaces\ServiceProviderInterface;
use Cblink\Service\Kennel\ServiceContainer;

/**
 * Class HttpRequestServiceProvider
 * @package Cblink\Service\Kennel\Providers
 */
class HttpRequestServiceProvider implements ServiceProviderInterface
{
    public function register(ServiceContainer $app)
    {
        $app['http'] = function($app){
            return new HttpRequest($app);
        };
    }
}
