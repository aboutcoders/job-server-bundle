<?php

namespace Abc\JobServerBundle\Tests\Functional\DependencyInjection;

use Abc\Job\HttpJobServer;
use Abc\Job\Symfony\DiUtils;
use Abc\JobServerBundle\Tests\Functional\WebTestCase;

/**
 * @group functional
 */
class HttpJobServerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $diUtils = new DiUtils();

        $consumer = static::$container->get($diUtils->format('http_job_server'));
        $this->assertInstanceOf(HttpJobServer::class, $consumer);
    }
}
