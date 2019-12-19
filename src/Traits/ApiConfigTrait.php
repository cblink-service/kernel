<?php
namespace Cblink\Service\Kennel\Traits;

trait ApiConfigTrait
{
    /**
     * @return bool
     */
    protected function isPrivate() : bool
    {
        return $this->getApp()->config('config.private', false);
    }

    /**
     * @return string
     */
    protected function baseUrl() : string
    {
        return $this->isPrivate() ?
            $this->getApp()->config('config.base_url', 'http://127.0.0.1/') :
            $this->getApp()->baseUrl();
    }

    /**
     * @return string
     */
    protected function getAppId() : string
    {
        return $this->getApp()->config('config.app_id', '');
    }

    /**
     * @return string
     */
    protected function getKey() : string
    {
        return $this->getApp()->config('config.key', '');
    }

    /**
     * @return string
     */
    protected function getSecret() : string
    {
        return $this->getApp()->config('config.secret', '');
    }
}
