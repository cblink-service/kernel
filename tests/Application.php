<?php


namespace Tests;

use Cblink\Service\Kennel\ServiceContainer;

class Application extends ServiceContainer
{
    protected function getCustomProviders(): array
    {
        return [];
    }
}
