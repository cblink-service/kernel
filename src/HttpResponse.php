<?php

namespace Cblink\Service\Kennel;

use Serializable;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Contracts\Support\Arrayable;
use Cblink\Service\Kennel\Exceptions\HttpRequestException;

/**
 * Class HttpResponse
 * @package Cblink\Service\Kennel
 */
class HttpResponse implements Arrayable, Serializable
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $origin;

    /**
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;

        $this->validated();
    }

    public function validated()
    {
        $response = $this->response;

        if ($response->getStatusCode() !== 200) {
            throw new HttpRequestException('请求错误：' . $response->getStatusCode());
        }

        $this->origin = $response->getBody()->getContents();

        $this->data = json_decode($this->origin, true);

        if (!is_array($this->data)) {
            throw new HttpRequestException('请求错误，返回内容为：' . $this->data);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function success()
    {
        return Arr::get($this->data, 'err_code') === 0;
    }

    /**
     * @return int|mixed
     */
    public function errCode()
    {
        return Arr::get($this->data, 'err_code');
    }

    /**
     * @return mixed|string
     */
    public function errMsg()
    {
        return Arr::get($this->data, 'err_msg', 'invalid error');
    }

    /**
     * @return mixed
     */
    public function meta()
    {
        return Arr::get($this->data, 'meta', []);
    }

    /**
     * @return array|mixed
     */
    public function all()
    {
        return Arr::get($this->data, 'data', []);
    }

    /**
     * @return string
     */
    public function origin()
    {
        return $this->origin;
    }

    /**
     * @return array|\string[][]
     */
    public function headers(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->data, 'data.' . $key, $default);
    }

    /**
     * @return array|mixed
     */
    public function toArray()
    {
        return $this->all();
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        return $this->data = unserialize($serialized);
    }
}
