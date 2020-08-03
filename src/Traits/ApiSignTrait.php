<?php

namespace Cblink\Service\Kennel\Traits;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use Cblink\Service\Kennel\ServiceContainer;

/**
 * Trait BuildSignTrait
 * @property ServiceContainer $app
 * @package Cblink\Service\Kennel\Traits
 * @mixin ApiConfigTrait
 */
trait ApiSignTrait
{
    /**
     * @param $uri
     * @param $method
     * @param array $options
     * @return array
     * @throws \Exception
     */
    protected function buildSign($uri, $method, array $options = [])
    {
        return $this->isPrivate() ?
            $this->buildPrivateSign($uri, $method, $options) :
            $this->buildGatewaySign($uri, $method, $options);
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array $options
     * @return array
     * @throws \Exception
     */
    protected function buildGatewaySign(string $uri, string $method, array $options = []): array
    {
        $contentType = isset($options['json']) ? 'application/json' : '';

        $date = gmdate("Y-m-d\TH:i:s\Z");

        $options['headers'] = array_merge(Arr::get($options, 'headers', []), [
            'Content-Type' => $contentType,
            'Accept' => "application/json",
            'Date' => $date,
            'X-Ca-Key' => $this->getKey(),
            'X-Ca-Nonce' => Uuid::uuid1()->toString(),
        ]);

        $params = $this->sortByArray($options['query'] ?? []);

        $buildUrl = '/' . (empty($params) ? $uri : ($uri . '?' . urldecode(http_build_query($params))));

        $headers = Arr::except($options['headers'], [
            'X-Ca-Signature', 'Accept', 'Content-MD5', 'Content-Type', 'Date', 'X-Ca-Signature-Headers'
        ]);
        ksort($headers);

        $headerString = "";
        foreach ($headers as $key => $val) {
            $headerString .= $key . ':' . $val . "\n";
        }

        $content = empty($options['json']) ? '' : base64_encode(md5(json_encode($options['json']), true));

        $signString = $method . "\n" .
            $options['headers']['Accept'] . "\n" .
            $content . "\n" .
            $options['headers']['Content-Type'] . "\n" .
            $date . "\n" .
            (empty($headerString) ? "\n" : $headerString) .
            $buildUrl;

        $options['headers'] = array_merge($options['headers'], [
            'Content-MD5' => $content,
            'X-Ca-Signature-Headers' => implode(',', array_keys($headers)),
            'X-Ca-Signature' => base64_encode(hash_hmac('sha256', $signString, $this->getSecret(), true))
        ]);

        return $options;
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array $options
     * @return array
     * @throws \Exception
     */
    protected function buildPrivateSign(string $uri, string $method, array $options = []): array
    {
        $params = $this->sortByArray($options['query'] ?? []);

        // url
        $uri = '/' . ltrim($uri, '/');

        // 内容默认为空
        $content = '';

        if (!empty($params)) {
            $uri .= '?' . urldecode(http_build_query($params));
        }

        if (!empty($options['json'])) {
            $content = base64_encode(md5(json_encode($options['json']), true));
        }

        $signString = $method . "\n" .
            $content . "\n" .
            "x-ca-proxy-signature-secret-key:" . $this->getKey() . "\n" .
            $uri;

        $signature = base64_encode(hash_hmac('sha256', $signString, $this->getSecret(), true));

        $options['headers'] = array_merge(Arr::get($options, 'headers', []), [
            'X-App-Id' => $this->getAppId(),
            'X-App-Request-Id' => Uuid::uuid4()->toString(),
            'X-Ca-Proxy-Signature-headers' => 'x-ca-proxy-signature-secret-key',
            'X-Ca-Proxy-Signature-secret-key' => $this->getKey(),
            'X-Ca-Proxy-Signature' => $signature
        ]);

        return $options;
    }

    /**
     * @param array $array
     * @return array
     */
    protected function sortByArray(array $array = []): array
    {
        $array = array_filter($array, function ($val) {
            return !is_null($val) && $val != "";
        });

        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $array[$key] = $this->sortByArray($val);
            }
        }
        ksort($array);

        return $array;
    }
}
