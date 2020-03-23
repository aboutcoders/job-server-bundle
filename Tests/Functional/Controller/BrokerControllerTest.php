<?php

namespace Abc\JobServerBundle\Tests\Functional\Controller;

use Abc\JobServerBundle\Controller\BrokerController;
use Abc\JobServerBundle\Tests\Functional\WebTestCase;

class BrokerControllerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $consumer = static::$container->get(BrokerController::class);
        $this->assertInstanceOf(BrokerController::class, $consumer);
    }
}
