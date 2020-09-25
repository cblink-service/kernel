<?php

namespace Tests;

use Cblink\Service\Kennel\ServiceContainer;
use Symfony\Component\HttpFoundation\Request;

class AppTest extends TestCase
{
    public function testApp()
    {
        $app = new Application([]);

        $this->assertInstanceOf(ServiceContainer::class, $app);
    }

    public function testRequest()
    {
        $app = new Application([]);

        $this->assertInstanceOf(Request::class, $app->request);
    }

    public function testLog()
    {
        $app = new Application([]);

        $this->assertNull($app->logger->info('hello world!'));
    }
}
