<?php

namespace Abc\JobServerBundle\Tests\Functional\DependencyInjection;

use Abc\Job\HttpRouteServer;
use Abc\Job\Symfony\DiUtils;
use Abc\JobServerBundle\Tests\Functional\WebTestCase;

/**
 * @group functional
 */
class HttpRouteServerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $diUtils = new DiUtils();

        $consumer = static::$container->get($diUtils->format('http_route_server'));
        $this->assertInstanceOf(HttpRouteServer::class, $consumer);
    }
}
