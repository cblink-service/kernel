<?php
namespace Cblink\Service\Kennel\Providers;

use Cblink\Service\Kennel\ServiceContainer;
use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class HttpClientServiceProvider
 * @package Cblink\Service\Kennel\Providers
 */
class HttpClientServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['http_client'] = function(ServiceContainer $app){
            return new Client($app->config->get('request', [
                'timeout' => 5,
                'http_errors' => false,
                'verify' => false,
            ]));
        };
    }
}
