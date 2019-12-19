<?php
namespace Cblink\Service\Kennel;

use Cblink\Service\Kennel\Providers\HttpClientServiceProvider;
use Cblink\Service\Kennel\Providers\HttpRequestServiceProvider;
use Cblink\Service\Kennel\Providers\LogServiceProvider;
use Illuminate\Config\Repository as Config;
use Pimple\Container;
use Closure;

/**
 * Class ServiceContainer
 * @property-read LogManager $logger
 * @property-read \GuzzleHttp\Client $http_client
 * @property-read HttpRequest $http
 * @package Cblink\Service\Kennel
 */
class ServiceContainer extends Container
{
    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var Config
     */
    public $config;

    public function __construct(array $config, array $values = [])
    {
        $this->setConfig($config);
        $this->registerProviders($this->getProviders());
        parent::__construct($values);
    }

    /**
     * @return array
     */
    public function getProviders() : array
    {
        return array_merge([
            LogServiceProvider::class,
            HttpClientServiceProvider::class,
            HttpRequestServiceProvider::class
        ], $this->providers);
    }

    /**
     * @param $name
     * @param \Closure $closure
     */
    public function rebind($name, Closure $closure)
    {
        if($this->offsetExists($name)){
            $this->offsetUnset($name);
        }

        $this->offsetSet($name, $closure);
    }

    /**
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }

    /**
     * @return string
     */
    public function appId() : string
    {
        return $this->config->get('app_id');
    }

    /**
     * @return string
     */
    public function appSecret() : string
    {
        return $this->config->get('secret');
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config = [])
    {
        $this->config = new Config($config);
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
