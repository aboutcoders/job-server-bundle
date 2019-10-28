<?php

namespace Abc\JobServerBundle\Tests\Functional\DependencyInjection;

use Abc\Job\JobServer;
use Abc\Job\Symfony\DiUtils;
use Abc\JobServerBundle\Tests\Functional\WebTestCase;

/**
 * @group functional
 */
class JobServerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $diUtils = new DiUtils();

        $consumer = static::$container->get($diUtils->format('job_server'));
        $this->assertInstanceOf(JobServer::class, $consumer);
    }
}
