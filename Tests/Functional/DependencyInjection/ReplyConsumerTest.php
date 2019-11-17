<?php

namespace Abc\JobWorkerBundle\Tests\Functional\DependencyInjection;

use Abc\Job\Interop\ReplyConsumer;
use Abc\Job\Symfony\DiUtils;
use Abc\JobServerBundle\Tests\Functional\WebTestCase;
use Interop\Queue\Processor;

/**
 * @group functional
 */
class ReplyConsumerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $diUtils = new DiUtils();

        $consumer = static::$container->get($diUtils->format('reply_consumer'));
        $this->assertInstanceOf(ReplyConsumer::class, $consumer);
        $this->assertInstanceOf(Processor::class, $consumer);
    }
}
