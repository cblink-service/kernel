<?php

namespace Cblink\Service\Kennel\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Cblink\Service\Kennel\LogManager;
use Cblink\Service\Kennel\ServiceContainer;

/**
 * Class LogServiceProvider
 * @package Cblink\Service\Kennel\Providers
 */
class LogServiceProvider implements ServiceProviderInterface
{
    protected $name = 'logger';

    public function register(Container $app)
    {
        /** @var ServiceContainer $app **/
        if (!$app->offsetExists($this->name)) {
            $app->setConfig(array_merge([// 日志配置
                'log' => [
                    'default' => 'single',
                    'channels' => [
                        'single' => [
                            'driver' => 'single',
                            'path' => \sys_get_temp_dir() . '/logs/cblink-service.log',
                            'level' => 'debug',
                        ],
                    ],
                ]], $app->config->all()));
            $app[$this->name] = function (ServiceContainer $app) {
                return new LogManager($app);
            };
        }
    }
}
