<?php
namespace Cblink\Service\Kennel\Providers;

use Cblink\Service\Kennel\Interfaces\ServiceProviderInterface;
use Cblink\Service\Kennel\ServiceContainer;
use GuzzleHttp\Client;

/**
 * Class HttpClientServiceProvider
 * @package Cblink\Service\Kennel\Providers
 */
class HttpClientServiceProvider implements ServiceProviderInterface
{
    public function register(ServiceContainer $app)
    {
        $app['http_client'] = function($app){
            return new Client($app->config->get('http'));
        };
    }
}
