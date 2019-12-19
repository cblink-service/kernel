<?php
namespace Cblink\Service\Kennel\Providers;

use Cblink\Service\Kennel\LogManager;
use Cblink\Service\Kennel\ServiceContainer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class LogServiceProvider
 * @package Cblink\Service\Kennel\Providers
 */
class LogServiceProvider implements ServiceProviderInterface
{
    protected $name = 'logger';

    public function register(Container $app)
    {
        if (!$app->offsetExists($this->name)){
            $app[$this->name] = function (ServiceContainer $app) {
                return new LogManager($app);
            };
        }
    }
}
