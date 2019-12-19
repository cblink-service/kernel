<?php
namespace Cblink\Service\Kennel;

/**
 * Class HttpRequest
 * @package Cblink\Service\Kennel
 */
class HttpRequest
{
    /**
     * @var ServiceContainer
     */
    protected $app;

    /**
     * @var string
     */
    protected $base_url = 'https://api.service.cblink.net/';

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $uri
     * @param array $payload
     * @return HttpResponse
     */
    public function get(string $uri, array $payload = [])
    {
        return $this->request($uri, 'GET', ['query' => $payload]);
    }

    /**
     * @param string $uri
     * @param array $payload
     * @return HttpResponse
     */
    public function post(string $uri, array $payload = [])
    {
        return $this->request($uri, 'POST', ['json' => $payload]);
    }

    /**
     * @param string $uri
     * @param array $payload
     * @return HttpResponse
     */
    public function put(string $uri, array $payload = [])
    {
        return $this->request($uri, 'PUT', ['json' => $payload]);
    }

    /**
     * @param string $uri
     * @param array $payload
     * @return HttpResponse
     */
    public function delete(string $uri, array $payload = [])
    {
        return $this->request($uri, 'DELETE', ['query' => $payload]);
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array $params
     * @return HttpResponse
     */
    public function request(string $uri, string $method, array $params = [])
    {
        $options = [
            'http_errors' => false,
            'verify' => false,
            'headers' => [
                'Authorization' => $this->getsign()
            ]
        ];

        return new HttpResponse($this->app->http_client->request(
            strtoupper($method),
            $this->url($uri),
            array_merge($options, $params)
        ));
    }

    /**
     * @param string $uri
     * @return string
     */
    public function url($uri = '') : string
    {
        return ($this->app->config->get('http.base_uri') ?: $this->base_url).$uri;
    }

    /**
     * @return string
     */
    public function getSign() : string
    {
        $data = [
            'id' => $this->app->appId(),
            'nonce' => uniqid(),
            'time' => time(),
        ];

        $data['sign'] = strtoupper(hash_hmac('sha1', http_build_query($data), $this->app->appSecret()));

        return base64_encode(implode(';', $data));
    }

}
