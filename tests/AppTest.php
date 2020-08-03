<?php

namespace Tests;

use Cblink\Service\Kennel\ServiceContainer;

class AppTest extends TestCase
{
    public function testApp()
    {
        $app = new Application([]);

        $this->assertInstanceOf(ServiceContainer::class, $app);
    }

    public function testLog()
    {
        $app = new Application([]);

        $this->assertNull($app->logger->info('hello world!'));
    }
}
