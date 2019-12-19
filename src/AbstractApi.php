<?php
namespace Cblink\Service\Kennel;

use Cblink\Service\Kennel\Traits\ApiSignTrait;
use Cblink\Service\Kennel\Traits\ApiConfigTrait;
use Cblink\Service\Kennel\Traits\ApiRequest;

/**
 * Class AbstractApi
 * @package Cblink\Service\Kennel
 */
abstract class AbstractApi
{
    use ApiRequest, ApiSignTrait, ApiConfigTrait;

    /**
     * @var ServiceContainer
     */
    protected $app;

    /**
     * AbstractApi constructor.
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array $options
     * @return HttpResponse
     * @throws \Exception
     */
    protected function request(string $uri, string $method, array $options = []) : HttpResponse
    {
        $method = strtoupper($method);

        return new HttpResponse($this->getApp()->getClient()->request(
            $method,
            $this->url($uri),
            $this->buildSign($uri, $method, $options)
        ));
    }

    /**
     * @return ServiceContainer
     */
    protected function getApp()
    {
        return $this->app;
    }

    /**
     * @param string $uri
     * @return string
     */
    protected function url($uri = '') : string
    {
        return  $this->baseUrl() . ltrim($uri, '/');
    }
}
