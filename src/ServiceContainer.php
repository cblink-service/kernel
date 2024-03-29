<?php

namespace Cblink\Service\Kennel;

use Closure;
use Pimple\Container;
use GuzzleHttp\Client;
use Illuminate\Config\Repository as Config;
use Symfony\Component\HttpFoundation\Request;
use Cblink\Service\Kennel\Providers\LogServiceProvider;
use Cblink\Service\Kennel\Providers\RequestServiceProvider;
use Cblink\Service\Kennel\Providers\HttpClientServiceProvider;

/**
 * Class ServiceContainer
 * @property LogManager         $logger
 * @property Client             $http_client
 * @property Request            $request
 * @package Cblink\Service\Kennel
 */
abstract class ServiceContainer extends Container
{
    /**
     * @var Config
     */
    public $config;

    /**
     * @var string
     */
    protected $base_url = 'https://api.service.cblink.net/';

    /**
     * ServiceContainer constructor.
     * @param array $config
     * @param array $values
     */
    public function __construct(array $config, array $values = [])
    {
        parent::__construct($values);
        $this->setConfig($config);
        $this->registerProviders($this->getProviders());
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config = [])
    {
        $this->config = new Config($config);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    /**
     * @return string
     */
    public function baseUrl()
    {
        return $this->base_url;
    }

    /**
     * @param array $providers
     */
    protected function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }

    /**
     * @return array
     */
    protected function getProviders(): array
    {
        return array_merge([
            LogServiceProvider::class,
            HttpClientServiceProvider::class,
            RequestServiceProvider::class,
        ], $this->getCustomProviders());
    }

    /**
     * @return array
     */
    abstract protected function getCustomProviders(): array;

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (!$this->offsetExists('request')) {
            $this->offsetSet('request', function () {
                return Request::createFromGlobals();
            });
        }

        return $this->offsetGet('request');
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (!$this->offsetExists('http_client')) {
            parent::register(new HttpClientServiceProvider());
        }

        return $this->http_client;
    }

    /**
     * @param $name
     * @param \Closure $closure
     */
    public function rebind($name, Closure $closure)
    {
        if ($this->offsetExists($name)) {
            $this->offsetUnset($name);
        }

        $this->offsetSet($name, $closure);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }
}
