<?php
namespace Cblink\Service\Kennel\Providers;

use Cblink\Service\Kennel\Interfaces\ServiceProviderInterface;
use Cblink\Service\Kennel\LogManager;
use Cblink\Service\Kennel\ServiceContainer;

/**
 * Class LogServiceProvider
 * @package Cblink\Service\Kennel\Providers
 */
class LogServiceProvider implements ServiceProviderInterface
{
    public function register(ServiceContainer $app)
    {
        $app['logger'] = function (ServiceContainer $app) {
            return new LogManager($app);
        };
    }
}
