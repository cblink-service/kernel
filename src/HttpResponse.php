<?php
namespace Cblink\Service\Kennel;

use ArrayAccess;
use Cblink\Service\Kennel\Exceptions\HttpRequestException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpResponse
 * @package Cblink\Service\Kennel
 */
class HttpResponse implements ArrayAccess, Arrayable
{
    /**
     * @var boolean
     */
    protected $success;

    /**
     * @var int
     */
    protected $errCode;

    /**
     * @var string
     */
    protected $errMsg;

    /**
     * @var array
     */
    protected $data = [];

    public function __construct(ResponseInterface $response)
    {
        if($response->getStatusCode() !== 200) {
            throw new HttpRequestException('请求错误：'.$response->getStatusCode());
        }

        $data = json_decode($response->getBody()->getContents(), true);
        if (!is_array($data)){
            throw new HttpRequestException('请求错误，返回内容为：'.$data);
        }

        $this->success = Arr::get($data, 'err_code') === 0;
        $this->errCode = Arr::get($data, 'err_code');
        $this->errMsg = Arr::get($data, 'msg');
        $this->data = Arr::get($data, 'data', []);
    }

    /**
     * @return bool
     */
    public function success()
    {
        return $this->success;
    }

    /**
     * @return int|mixed
     */
    public function errCode()
    {
        return $this->errCode;
    }

    /**
     * @return mixed|string
     */
    public function errMsg()
    {
        return $this->errMsg;
    }

    /**
     * @return array|mixed
     */
    public function data()
    {
        return $this->data;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset)
    {
        if($this->offsetExists($offset)){
            return $this->data[$offset];
        }
        return "";
    }

    public function toArray()
    {
        return $this->data;
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}
