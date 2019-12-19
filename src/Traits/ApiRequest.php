<?php
namespace Cblink\Service\Kennel\Traits;

use Cblink\Service\Kennel\HttpResponse;

/**
 * Trait HttpRequest
 * @package Cblink\Service\Kennel\Traits
 */
trait ApiRequest
{
    /**
     * @param string $uri
     * @param array $payload
     * @return HttpResponse
     */
    protected function get(string $uri, array $payload = [])
    {
        return $this->request($uri, 'GET', ['query' => $payload]);
    }

    /**
     * @param string $uri
     * @param array $payload
     * @return HttpResponse
     */
    protected function post(string $uri, array $payload = [])
    {
        return $this->request($uri, 'POST', ['json' => $payload]);
    }

    /**
     * @param string $uri
     * @param array $payload
     * @return HttpResponse
     */
    protected function put(string $uri, array $payload = [])
    {
        return $this->request($uri, 'PUT', ['json' => $payload]);
    }

    /**
     * @param string $uri
     * @param array $payload
     * @return HttpResponse
     */
    protected function delete(string $uri, array $payload = [])
    {
        return $this->request($uri, 'DELETE', ['query' => $payload]);
    }
}
